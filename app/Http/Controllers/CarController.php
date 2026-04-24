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
            ->where(fn($q) => $q->whereNull('listing_type')
                ->orWhereIn('listing_type', ['sale', 'both']))
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

        // Check if buyer already reviewed this car (purchase review)
        $alreadyReviewed = false;
        $hasPurchased    = false;
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            $hasPurchased    = auth()->user()->orders()
                ->where('car_id', $car->id)
                ->where('status', 'completed')
                ->exists();
            $alreadyReviewed = $car->reviews
                ->whereNull('car_rental_id')
                ->contains('buyer_id', auth()->id());
        }

        // Check if buyer has a completed rental and hasn't reviewed it yet
        $hasRented             = false;
        $alreadyReviewedRental = false;
        $completedRental       = null;
        try {
            if (auth()->check() && auth()->user()->hasRole('buyer')) {
                $completedRental = \App\Models\CarRental::where('renter_id', auth()->id())
                    ->where('car_id', $car->id)
                    ->where('status', 'completed')
                    ->latest()
                    ->first();

                if ($completedRental) {
                    $hasRented = true;
                    $alreadyReviewedRental = \App\Models\Review::where('buyer_id', auth()->id())
                        ->where('car_rental_id', $completedRental->id)
                        ->exists();
                }
            }
        } catch (\Exception $e) {
            // car_rentals table doesn't exist yet — migration not run
        }

        // ── Rental state ──────────────────────────────────────────────
        $alreadyRented = false;
        $blockedBySaleRental = false;
        try {
            if (auth()->check() && auth()->user()->hasRole('buyer')) {
                $alreadyRented = \App\Models\CarRental::where('renter_id', auth()->id())
                    ->where('car_id', $car->id)
                    ->whereIn('status', ['pending', 'confirmed', 'active'])
                    ->exists();
            }
            // Block sale orders if car is currently out on an active rental
            $blockedBySaleRental = $car->isSaleable() ? $car->hasActiveRental() : false;
        } catch (\Exception $e) {
            // car_rentals table doesn't exist yet — migration not run
        }

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
            'blockedBySaleRental',
            'hasRented',
            'alreadyReviewedRental',
            'completedRental'
        ));
    }
}