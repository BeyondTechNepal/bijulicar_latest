<?php

namespace App\Http\Controllers;

use App\Models\Car;

class CarController extends Controller
{
    public function show(Car $car)
    {
        // Only show available cars to the public
         abort_if(in_array($car->status, ['sold', 'inactive']), 404);

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

        $avgRating = $car->reviews->avg('rating');
        $reviewCount = $car->reviews->count();

        // Check if logged-in buyer already ordered this car
        $alreadyOrdered = false;
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            $alreadyOrdered = auth()->user()->orders()
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed', 'completed'])
                ->exists();
        }

        $alreadyPreOrdered = false;
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            $alreadyPreOrdered = \App\Models\PreOrder::where('buyer_id', auth()->id())
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending_deposit', 'deposit_paid'])
                ->exists();
        }

        // Check if buyer already reviewed this car
        $alreadyReviewed = false;
        $hasPurchased = false;
        if (auth()->check() && auth()->user()->hasRole('buyer')) {
            $hasPurchased = auth()->user()->orders()
                ->where('car_id', $car->id)
                ->where('status', 'completed')
                ->exists();
            $alreadyReviewed = $car->reviews->contains('buyer_id', auth()->id());
        }

        return view('frontend.pages.car_detail', compact(
            'car',
            'otherListings',
            'avgRating',
            'reviewCount',
            'alreadyOrdered',
            'alreadyReviewed',
            'hasPurchased',
            'alreadyPreOrdered'
        ));
    }
}