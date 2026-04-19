<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Negotiation;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerNegotiationController extends Controller
{
    private function authUserId(): int
    {
        return Auth::guard('web')->id();
    }

    private function context(): array
    {
        $user = Auth::guard('web')->user();
        if ($user && $user->hasRole('business')) {
            return ['prefix' => 'business', 'layout' => 'dashboard.business.layout'];
        }
        return ['prefix' => 'seller', 'layout' => 'dashboard.seller.layout'];
    }

    /** List all negotiations for this seller/business */
    public function index()
    {
        $ctx = $this->context();

        $negotiations = Negotiation::where('seller_id', $this->authUserId())
            ->with(['car', 'buyer'])
            ->latest()
            ->paginate(10);

        return view('dashboard.seller.negotiations.index', array_merge(compact('negotiations'), $ctx));
    }

    /** Show a single negotiation */
    public function show(Negotiation $negotiation)
    {
        abort_if($negotiation->seller_id !== $this->authUserId(), 403);
        $ctx = $this->context();
        $negotiation->load(['car', 'buyer']);

        return view('dashboard.seller.negotiations.show', array_merge(compact('negotiation'), $ctx));
    }

    /**
     * Seller accepts the buyer's current offer.
     * The negotiated price is locked in — buyer will be notified to place order.
     */
    public function accept(Negotiation $negotiation)
    {
        abort_if($negotiation->seller_id !== $this->authUserId(), 403);
        abort_if(!$negotiation->isPendingSeller(), 422, 'Nothing to accept right now.');

        $negotiation->update([
            'status'     => 'accepted',
            'expires_at' => null,
        ]);

        app(NotificationService::class)->negotiationAcceptedBySeller($negotiation);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.negotiations.show', $negotiation)
            ->with('success', 'Offer accepted! The buyer has been notified and can now place their order.');
    }

    /**
     * Seller sends a counter-offer back to the buyer.
     */
    public function counter(Request $request, Negotiation $negotiation)
    {
        abort_if($negotiation->seller_id !== $this->authUserId(), 403);
        abort_if(!$negotiation->isPendingSeller(), 422, 'It is not your turn to counter.');
        abort_if(!$negotiation->canCounter(),      422, 'Maximum negotiation rounds reached.');

        $request->validate([
            'offered_price' => ['required', 'integer', 'min:1'],
            'message'       => ['nullable', 'string', 'max:300'],
        ]);

        abort_if(
            $request->offered_price >= $negotiation->listed_price,
            422,
            'Your counter offer must be lower than the listed price.'
        );

        abort_if(
            $request->offered_price <= 0,
            422,
            'Counter offer price must be greater than zero.'
        );

        $negotiation->update([
            'offered_price' => $request->offered_price,
            'status'        => 'pending_buyer',
            'rounds'        => $negotiation->rounds + 1,
            'message'       => $request->message,
            'expires_at'    => now()->addHours(48),
        ]);

        app(NotificationService::class)->negotiationCounteredBySeller($negotiation);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.negotiations.show', $negotiation)
            ->with('success', 'Counter offer sent to the buyer.');
    }

    /**
     * Seller declines the buyer's offer entirely.
     */
    public function decline(Negotiation $negotiation)
    {
        abort_if($negotiation->seller_id !== $this->authUserId(), 403);
        abort_if(!$negotiation->isPendingSeller(), 422, 'Nothing to decline right now.');

        $negotiation->update([
            'status'     => 'declined',
            'expires_at' => null,
        ]);

        app(NotificationService::class)->negotiationDeclinedBySeller($negotiation);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.negotiations.index')
            ->with('success', 'Offer declined. The buyer has been notified.');
    }
}