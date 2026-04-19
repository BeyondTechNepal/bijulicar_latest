<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Negotiation;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerNegotiationController extends Controller
{
    /** List all negotiations for the logged-in buyer */
    public function index()
    {
        $negotiations = Negotiation::where('buyer_id', Auth::id())
            ->with(['car', 'seller'])
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.negotiations.index', compact('negotiations'));
    }

    /** Show a single negotiation thread */
    public function show(Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id !== Auth::id(), 403);
        $negotiation->load(['car', 'seller']);

        return view('dashboard.buyer.negotiations.show', compact('negotiation'));
    }

    /**
     * Buyer starts a new negotiation by submitting an offer price.
     * Called from the car detail page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id'        => ['required', 'exists:cars,id'],
            'offered_price' => ['required', 'integer', 'min:1'],
            'message'       => ['nullable', 'string', 'max:300'],
        ]);

        $car     = Car::findOrFail($request->car_id);
        $buyerId = Auth::id();

        abort_if(!$car->price_negotiable,              422, 'This listing is not open to negotiation.');
        abort_if($car->status === 'sold',              422, 'This car has already been sold.');
        abort_if(!$car->isAvailable(),                 422, 'This car is no longer available.');
        abort_if($car->seller_id === $buyerId,         422, 'You cannot negotiate on your own listing.');
        abort_if($request->offered_price >= $car->price, 422, 'Your offer must be lower than the listed price.');

        // Only one active negotiation per buyer per car
        $existing = Negotiation::where('buyer_id', $buyerId)
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending_seller', 'pending_buyer'])
            ->first();

        abort_if($existing, 422, 'You already have an active negotiation for this car.');

        $negotiation = Negotiation::create([
            'buyer_id'      => $buyerId,
            'seller_id'     => $car->seller_id,
            'car_id'        => $car->id,
            'offered_price' => $request->offered_price,
            'listed_price'  => $car->price,
            'status'        => 'pending_seller',
            'rounds'        => 1,
            'message'       => $request->message,
            'expires_at'    => now()->addHours(48),
        ]);

        app(NotificationService::class)->negotiationOfferReceived($negotiation);

        return redirect()
            ->route('buyer.negotiations.show', $negotiation)
            ->with('success', 'Your offer has been sent to the seller. You will be notified once they respond.');
    }

    /**
     * Buyer accepts the seller's counter-offer price.
     * This locks in the negotiated price — buyer can then place an order.
     */
    public function accept(Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id !== Auth::id(), 403);
        abort_if(!$negotiation->isPendingBuyer(), 422, 'Nothing to accept right now.');

        $negotiation->update([
            'status'     => 'accepted',
            'expires_at' => null,
        ]);

        app(NotificationService::class)->negotiationAcceptedBySeller($negotiation);

        return redirect()
            ->route('buyer.negotiations.show', $negotiation)
            ->with('success', 'You accepted the offer! You can now place your order at the agreed price.');
    }

    /**
     * Buyer counters the seller's counter-offer with a new price.
     */
    public function counter(Request $request, Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id !== Auth::id(), 403);
        abort_if(!$negotiation->isPendingBuyer(), 422, 'It is not your turn to counter.');
        abort_if(!$negotiation->canCounter(),     422, 'Maximum negotiation rounds reached.');

        $request->validate([
            'offered_price' => ['required', 'integer', 'min:1'],
            'message'       => ['nullable', 'string', 'max:300'],
        ]);

        abort_if(
            $request->offered_price >= $negotiation->listed_price,
            422,
            'Your counter offer must be lower than the listed price.'
        );

        $negotiation->update([
            'offered_price' => $request->offered_price,
            'status'        => 'pending_seller',
            'rounds'        => $negotiation->rounds + 1,
            'message'       => $request->message,
            'expires_at'    => now()->addHours(48),
        ]);

        app(NotificationService::class)->negotiationOfferReceived($negotiation);

        return redirect()
            ->route('buyer.negotiations.show', $negotiation)
            ->with('success', 'Counter offer sent. Waiting for the seller to respond.');
    }

    /**
     * Buyer cancels/withdraws their active negotiation.
     */
    public function cancel(Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id !== Auth::id(), 403);
        abort_if($negotiation->isClosed(), 422, 'This negotiation is already closed.');

        $negotiation->update(['status' => 'cancelled', 'expires_at' => null]);

        return redirect()
            ->route('buyer.negotiations.index')
            ->with('success', 'Negotiation cancelled.');
    }
}