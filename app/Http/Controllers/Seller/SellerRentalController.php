<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\CarRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerRentalController extends Controller
{
    // ── Role-aware context ────────────────────────────────────────────
    // Mirrors SellerOrderController — same controller serves both
    // seller and business roles by switching prefix and layout.

    private function authUser()
    {
        return Auth::guard('web')->user();
    }

    private function context(): array
    {
        if ($this->authUser()?->hasRole('business')) {
            return ['prefix' => 'business', 'layout' => 'dashboard.business.layout'];
        }
        return ['prefix' => 'seller', 'layout' => 'dashboard.seller.layout'];
    }

    // ── List all rental bookings for this owner ───────────────────────

    public function index()
    {
        $ctx    = $this->context();
        $userId = Auth::guard('web')->id();

        $rentals = CarRental::where('owner_id', $userId)
            ->with(['car' => fn ($q) => $q->withTrashed(), 'renter'])
            ->latest()
            ->paginate(10);

        return view('dashboard.seller.rentals.index', array_merge(compact('rentals'), $ctx));
    }

    // ── Show a single rental booking ──────────────────────────────────

    public function show(CarRental $carRental)
    {
        abort_if($carRental->owner_id !== Auth::guard('web')->id(), 403);

        $ctx = $this->context();
        $carRental->load(['car' => fn ($q) => $q->withTrashed(), 'renter']);

        return view('dashboard.seller.rentals.show', array_merge(compact('carRental'), $ctx));
    }

    // ── Confirm: pending → confirmed ─────────────────────────────────

    public function confirm(CarRental $carRental)
    {
        abort_if($carRental->owner_id !== Auth::guard('web')->id(), 403);
        abort_if($carRental->status !== 'pending', 422, 'Only pending bookings can be confirmed.');

        // Check for overlapping confirmed/active rentals before locking in dates
        if ($carRental->car && $carRental->car->hasOverlappingRental(
            $carRental->pickup_date,
            $carRental->return_date,
            $carRental->id  // exclude self
        )) {
            return redirect()
                ->back()
                ->with('error', 'These dates overlap with another confirmed rental for this car. Please cancel the conflicting booking first.');
        }

        $carRental->update(['status' => 'confirmed']);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.rentals.show', $carRental->id)
            ->with('success', 'Rental booking confirmed. The renter has been notified.');
    }

    // ── Activate: confirmed → active (car handed over) ────────────────

    public function activate(CarRental $carRental)
    {
        abort_if($carRental->owner_id !== Auth::guard('web')->id(), 403);
        abort_if($carRental->status !== 'confirmed', 422, 'Only confirmed bookings can be marked as active.');

        $carRental->update(['status' => 'active']);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.rentals.show', $carRental->id)
            ->with('success', 'Rental marked as active — car is now with the renter.');
    }

    // ── Complete: active → completed (car returned) ───────────────────

    public function complete(Request $request, CarRental $carRental)
    {
        abort_if($carRental->owner_id !== Auth::guard('web')->id(), 403);
        abort_if($carRental->status !== 'active', 422, 'Only active rentals can be marked as completed.');

        $request->validate([
            'actual_return_date' => ['nullable', 'date'],
        ]);

        $carRental->update([
            'status'             => 'completed',
            'actual_return_date' => $request->actual_return_date ?? today(),
        ]);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.rentals.show', $carRental->id)
            ->with('success', 'Rental marked as completed. Car has been returned.');
    }

    // ── Cancel: pending or confirmed → cancelled ──────────────────────

    public function cancel(Request $request, CarRental $carRental)
    {
        abort_if($carRental->owner_id !== Auth::guard('web')->id(), 403);
        abort_if(!$carRental->isCancellable(), 422, 'This booking can no longer be cancelled.');

        $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $carRental->update([
            'status'              => 'cancelled',
            'cancelled_by'        => 'owner',
            'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by owner.',
        ]);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.rentals.index')
            ->with('success', 'Rental booking cancelled.');
    }
}