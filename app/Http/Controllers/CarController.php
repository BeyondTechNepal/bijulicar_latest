<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Car;

class CarController extends Controller
{
    public function show(Car $car)
    {
        // Block truly inactive/deleted listings, but let sold cars through
        // so buyers who arrive via homepage "Recently Added" see a proper
        // "Sold Out" page instead of a confusing 404.
        abort_if($car->status === 'inactive', 404);

        $isSoldOut = $car->status === 'sold';

        $car->load([
            'seller',
            'images',
            'reviews' => fn($q) => $q->with('buyer')->latest()->take(10),
        ]);

        // Other listings by the same seller (excluding current)
        $otherListings = Car::where('seller_id', $car->seller_id)
            ->where('id', '!=', $car->id)
            ->where('status', 'available')
            ->with('primaryImage')
            ->latest()
            ->take(3)
            ->get();

        $avgRating   = $car->reviews->avg('rating');
        $reviewCount = $car->reviews->count();

        // Check active/accepted negotiation for this buyer+car
        $activeNegotiation = null;
        if (!$isSoldOut && auth()->check() && auth()->user()->hasRole('buyer')) {
            $activeNegotiation = \App\Models\Negotiation::where('buyer_id', auth()->id())
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending_seller', 'pending_buyer', 'accepted'])
                ->first();
        }

        // Check if logged-in buyer already ordered this car
        $alreadyOrdered = false;
        if (!$isSoldOut && auth()->check() && auth()->user()->hasRole('buyer')) {
            $alreadyOrdered = auth()->user()->orders()
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();
        }

        $alreadyPreOrdered = false;
        if (!$isSoldOut && auth()->check() && auth()->user()->hasRole('buyer')) {
            $alreadyPreOrdered = \App\Models\PreOrder::where('buyer_id', auth()->id())
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending_deposit', 'deposit_paid'])
                ->exists();
        }

        // Check if buyer already reviewed this car
        $alreadyReviewed = false;
        $hasPurchased    = false;
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            $hasPurchased    = auth()->user()->orders()
                ->where('car_id', $car->id)
                ->where('status', 'completed')
                ->exists();
            $alreadyReviewed = $car->reviews->contains('buyer_id', auth()->id());
        }

        // ── Rental state ──────────────────────────────────────────────
        $alreadyRented = false;
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            $alreadyRented = \App\Models\CarRental::where('renter_id', auth()->id())
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->exists();
        }

        // Block sale orders if car is currently out on an active rental
        $blockedBySaleRental = $car->isRentable() && !$car->isSaleable() ? false : $car->hasActiveRental();

        // ── Sidebar ads for the car detail page (priority DESC) ──────────
        $carDetailAds = Advertisement::liveForPlacement('car_detail_horizontal')->get();

        return view('frontend.pages.car_detail', compact(
            'car',
            'isSoldOut',
            'activeNegotiation',
            'otherListings',
            'avgRating',
            'reviewCount',
            'alreadyOrdered',
            'alreadyReviewed',
            'hasPurchased',
            'alreadyPreOrdered',
            'carDetailAds',
            'alreadyRented',
            'blockedBySaleRental'
        ));
    }
}