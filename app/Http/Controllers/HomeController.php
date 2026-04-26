<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    // Cache key constants — used here and in bust helpers across other controllers
    public const CACHE_FLEET_COUNTS    = 'home.fleet_counts';
    public const CACHE_RECENT_CARS     = 'home.recent_cars';
    public const CACHE_HOME_ADS        = 'home.ads';
    public const CACHE_FEATURED_BIZ    = 'home.featured_businesses';
    public const CACHE_RENTABLE_COUNT  = 'home.rentable_count';

    public function index()
    {
        // Fleet counts — only change when a car is created/updated/deleted.
        // Busted in SellerCarController. TTL is a safety net for edge cases.
        [$evCount, $hybridCount, $classicCount] = Cache::remember(
            self::CACHE_FLEET_COUNTS,
            now()->addMinutes(10),
            fn () => [
                Car::where('status', 'available')->where('drivetrain', 'ev')->count(),
                Car::where('status', 'available')->where('drivetrain', 'hybrid')->count(),
                Car::where('status', 'available')->whereIn('drivetrain', ['petrol', 'diesel'])->count(),
            ]
        );

        // Recent listings — busted on car create/update/delete.
        $recentCars = Cache::remember(
            self::CACHE_RECENT_CARS,
            now()->addMinutes(5),
            fn () => Car::whereIn('status', ['available', 'upcoming'])
                ->with(['primaryImage', 'seller'])
                ->latest()
                ->take(10)
                ->get()
        );

        // Home ads — busted when admin publishes or deletes an ad.
        $homeAds = Cache::remember(
            self::CACHE_HOME_ADS,
            now()->addMinutes(5),
            fn () => Advertisement::with('car')
                ->where('placement', 'home')
                ->where('is_active', true)
                ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
                ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
                ->get()
        );

        // Featured businesses — heaviest query. Busted on business approval,
        // car mutations, and new reviews. TTL is a safety net.
        $featuredBusinesses = Cache::remember(
            self::CACHE_FEATURED_BIZ,
            now()->addMinutes(15),
            fn () => User::role('business')
                ->with(['businessVerification', 'listedCars'])
                ->withAvg('receivedReviews', 'rating')
                ->withCount('receivedReviews')
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
                        'profile_photo'   => $user->profile_photo ? \Illuminate\Support\Facades\Storage::url($user->profile_photo) : null,
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
                ->values()
        );

        // Rentable cars count — busted when a car listing_type or status changes.
        $rentableCount = Cache::remember(
            self::CACHE_RENTABLE_COUNT,
            now()->addMinutes(10),
            fn () => Car::whereIn('listing_type', ['rent', 'both'])
                ->where('status', 'available')
                ->count()
        );

        return view('frontend.pages.home', compact(
            'recentCars',
            'evCount',
            'hybridCount',
            'classicCount',
            'homeAds',
            'featuredBusinesses',
            'rentableCount'
        ));
    }
}