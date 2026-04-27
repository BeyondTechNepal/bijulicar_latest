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
    private function authUserId(): int
    {
        return Auth::guard('web')->id();
    }

    public function index()
    {
        $negotiations = Negotiation::where('buyer_id', $this->authUserId())
            ->with(['car', 'seller'])
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.negotiations.index', compact('negotiations'));
    }

    public function show(Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id != $this->authUserId(), 403);
        $negotiation->load(['car', 'seller']);

        return view('dashboard.buyer.negotiations.show', compact('negotiation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id'        => ['required', 'exists:cars,id'],
            'offered_price' => ['required', 'integer', 'min:1'],
            'message'       => ['nullable', 'string', 'max:300'],
        ]);

        $buyerId = $this->authUserId();
        $car     = Car::findOrFail($request->car_id);

        abort_if(!$car->isAvailable(), 422, 'This listing is no longer available.');
        abort_if($car->seller_id == $buyerId, 422, 'You cannot negotiate on your own listing.');
        abort_if(
            $request->offered_price >= $car->price,
            422,
            'Your offer must be lower than the listed price.'
        );

        // Prevent duplicate active negotiations
        $existing = Negotiation::where('buyer_id', $buyerId)
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending_seller', 'pending_buyer', 'accepted'])
            ->first();

        abort_if($existing, 422, 'You already have an active negotiation for this car.');

        $negotiation = Negotiation::create([
            'buyer_id'      => $buyerId,
            'seller_id'     => $car->seller_id,
            'car_id'        => $car->id,
            'listed_price'  => $car->price,
            'offered_price' => $request->offered_price,
            'status'        => 'pending_seller',
            'rounds'        => 1,
            'message'       => $request->message,
            'expires_at'    => now()->addHours(48),
        ]);

        app(NotificationService::class)->negotiationOfferReceived($negotiation);

        return redirect()
            ->route('buyer.negotiations.show', $negotiation)
            ->with('success', 'Offer sent! The seller will respond shortly.');
    }

    public function accept(Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id != $this->authUserId(), 403);
        abort_if(!$negotiation->isPendingBuyer(), 422, 'Nothing to accept right now.');

        $negotiation->update(['status' => 'accepted', 'expires_at' => null]);

        app(NotificationService::class)->negotiationAcceptedByBuyer($negotiation);

        return redirect()
            ->route('buyer.negotiations.show', $negotiation)
            ->with('success', 'Deal accepted! You can now place your order at the negotiated price.');
    }

    public function counter(Request $request, Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id != $this->authUserId(), 403);
        abort_if(!$negotiation->isPendingBuyer(), 422, 'It is not your turn to counter.');
        abort_if(!$negotiation->canCounter(),     422, 'Maximum negotiation rounds reached.');

        $request->validate([
            'offered_price' => ['required', 'integer', 'min:1'],
            'message'       => ['nullable', 'string', 'max:300'],
        ]);

        abort_if(
            $request->offered_price >= $negotiation->listed_price,
            422,
            'Your offer must be lower than the listed price.'
        );

        $negotiation->update([
            'offered_price' => $request->offered_price,
            'status'        => 'pending_seller',
            'rounds'        => $negotiation->rounds + 1,
            'message'       => $request->message,
            'expires_at'    => now()->addHours(48),
        ]);

        app(NotificationService::class)->negotiationCounteredByBuyer($negotiation);

        return redirect()
            ->route('buyer.negotiations.show', $negotiation)
            ->with('success', 'Counter offer sent to the seller.');
    }

    public function cancel(Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id != $this->authUserId(), 403);
        abort_if($negotiation->isClosed(), 422, 'This negotiation is already closed.');

        $negotiation->update(['status' => 'cancelled', 'expires_at' => null]);

        return redirect()
            ->route('buyer.negotiations.index')
            ->with('success', 'Negotiation withdrawn.');
    }
}