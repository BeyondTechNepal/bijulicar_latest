<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Advertisement;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Latest 6 available listings from sellers & businesses
        $recentCars = Car::whereIn('status', ['available', 'upcoming'])
            ->with(['primaryImage', 'seller'])
            ->latest()
            ->take(10)
            ->get();

        // Live counts for the fleet cards
        $evCount      = Car::where('status', 'available')->where('drivetrain', 'ev')->count();
        $hybridCount  = Car::where('status', 'available')->where('drivetrain', 'hybrid')->count();
        $classicCount = Car::where('status', 'available')->whereIn('drivetrain', ['petrol', 'diesel'])->count();

        $homeAds = Advertisement::with('car')
            ->where('placement', 'home')
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->get();

        // Featured businesses — top 8 verified businesses by active listing count.
        //
        // FIX: The previous implementation called Review::avg() and Review::count()
        // inside a PHP loop, producing 2 extra queries per business user (N+1 bomb).
        // Now we use withAvg() and withCount() so Laravel resolves both aggregates in
        // a single extra query each (3 total: users + avg + count), regardless of how
        // many business users exist.
        $featuredBusinesses = User::role('business')
            ->with(['businessVerification', 'listedCars'])
            ->withAvg('receivedReviews', 'rating')   // → $user->received_reviews_avg_rating
            ->withCount('receivedReviews')            // → $user->received_reviews_count
            ->whereHas('businessVerification', fn($q) => $q->where('status', 'approved'))
            ->get()
            ->map(function ($user) {
                $activeCars  = $user->listedCars->whereIn('status', ['available', 'upcoming']);
                $avgRating   = $user->received_reviews_avg_rating ?? 0;
                $reviewCount = $user->received_reviews_count ?? 0;
                $location    = $activeCars->pluck('location')->filter()->first() ?? 'Nepal';
                $drivetrains = $activeCars->pluck('drivetrain')->unique();
                if ($drivetrains->count() > 1)             $spec = 'Multi-Brand';
                elseif ($drivetrains->contains('ev'))      $spec = 'EV Dealer';
                elseif ($drivetrains->contains('hybrid'))  $spec = 'Hybrid';
                else                                       $spec = 'Traditional';

                return [
                    'id'              => $user->id,
                    'name'            => $user->businessVerification->business_name ?? $user->name,
                    'initials'        => strtoupper(substr($user->businessVerification->business_name ?? $user->name, 0, 2)),
                    'active_listings' => $activeCars->count(),
                    'avg_rating'      => round($avgRating, 1),
                    'review_count'    => $reviewCount,
                    'specialization'  => $spec,
                    'location'        => $location,
                    'profile_url'     => route('businesses.show', $user->id),
                ];
            })
            ->sortByDesc('active_listings')
            ->take(8)
            ->values();

        return view('frontend.pages.home', compact(
            'recentCars',
            'evCount',
            'hybridCount',
            'classicCount',
            'homeAds',
            'featuredBusinesses'
        ));
    }
}