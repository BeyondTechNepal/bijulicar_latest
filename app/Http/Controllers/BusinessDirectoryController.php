<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\NewLocation;
use App\Models\User;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;

class BusinessDirectoryController extends Controller
{
    /**
     * Public business directory listing page.
     * Shows all approved/verified businesses with stats.
     */
    public function index(Request $request)
    {
        $query = User::role('business')
            ->with(['businessVerification', 'listedCars'])
            ->whereHas('businessVerification', fn($q) => $q->where('status', 'approved'));

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('listedCars', fn($q) =>
                $q->where('location', 'like', '%' . $request->location . '%')
                  ->whereIn('status', ['available', 'upcoming'])
            );
        }

        // Filter by specialization (drivetrain)
        if ($request->filled('specialization') && $request->specialization !== 'all') {
            $query->whereHas('listedCars', fn($q) =>
                $q->where('drivetrain', $request->specialization)
                  ->whereIn('status', ['available', 'upcoming'])
            );
        }

        $sort       = $request->get('sort', 'listings');
        $businesses = $query->get()->map(function ($user) {
            $activeCars  = $user->listedCars->whereIn('status', ['available', 'upcoming']);
            $allReviews  = Review::where('seller_id', $user->id)->get();
            $avgRating   = $allReviews->avg('rating') ?? 0;
            $reviewCount = $allReviews->count();

            $drivetrains = $activeCars->pluck('drivetrain')->unique();
            if ($drivetrains->count() > 1) {
                $spec = 'Multi-Brand';
            } elseif ($drivetrains->contains('ev')) {
                $spec = 'EV Dealer';
            } elseif ($drivetrains->contains('hybrid')) {
                $spec = 'Hybrid';
            } else {
                $spec = 'Traditional';
            }

            $location = $activeCars->pluck('location')->filter()->first() ?? 'Nepal';

            return [
                'id'              => $user->id,
                'name'            => $user->businessVerification->business_name ?? $user->name,
                'contact'         => $user->businessVerification->contact ?? null,
                'initials'        => strtoupper(substr($user->businessVerification->business_name ?? $user->name, 0, 2)),
                'profile_photo'   => $user->profile_photo ? \Illuminate\Support\Facades\Storage::url($user->profile_photo) : null,
                'active_listings' => $activeCars->count(),
                'avg_rating'      => round($avgRating, 1),
                'review_count'    => $reviewCount,
                'specialization'  => $spec,
                'location'        => $location,
                'profile_url'     => route('businesses.show', $user->id),
                'user'            => $user,
            ];
        });

        if ($sort === 'rating') {
            $businesses = $businesses->sortByDesc('avg_rating');
        } elseif ($sort === 'reviews') {
            $businesses = $businesses->sortByDesc('review_count');
        } else {
            $businesses = $businesses->sortByDesc('active_listings');
        }

        $totalBusinesses = $businesses->count();
        $totalListings   = $businesses->sum('active_listings');
        $totalCities     = Car::whereIn('status', ['available', 'upcoming'])
                              ->pluck('location')
                              ->map(fn($l) => explode(',', $l)[0])
                              ->unique()
                              ->count();

        $latestNews = \App\Models\BusinessNews::where('is_published', true)
            ->with(['business.businessVerification', 'newscategory'])
            ->latest()
            ->take(6)
            ->get();

        $cities = Car::whereIn('status', ['available', 'upcoming'])
            ->pluck('location')
            ->map(fn($l) => trim(explode(',', $l)[0]))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // ── Horizontal banner ads for the business directory (priority DESC)
        $businessBannerAds = Advertisement::liveForPlacement('business_banner')->get();

        return view('frontend.pages.businesses', compact(
            'businesses',
            'totalBusinesses',
            'totalListings',
            'totalCities',
            'latestNews',
            'cities',
            'businessBannerAds'
        ));
    }

    /**
     * Individual business profile page.
     */
    public function show(int $id)
    {
        $user = User::role('business')
            ->with(['businessVerification', 'listedCars.primaryImage', 'listedCars.reviews'])
            ->whereHas('businessVerification', fn($q) => $q->where('status', 'approved'))
            ->findOrFail($id);

        $activeCars  = $user->listedCars->whereIn('status', ['available', 'upcoming'])->values();
        $allReviews  = Review::where('seller_id', $user->id)->with(['buyer', 'car'])->latest()->take(10)->get();
        $avgRating   = $allReviews->avg('rating') ?? 0;
        $reviewCount = $allReviews->count();

        $businessName = $user->businessVerification->business_name ?? $user->name;
        $contact      = $user->businessVerification->contact ?? null;
        $email        = $user->email ?? null;
        $mapLocation  = NewLocation::where('user_id', $user->id)
                            ->where('type', 'business')
                            ->where('is_active', true)
                            ->first();

        $drivetrains = $activeCars->pluck('drivetrain')->unique();
        if ($drivetrains->count() > 1) {
            $spec = 'Multi-Brand';
        } elseif ($drivetrains->contains('ev')) {
            $spec = 'EV Dealer';
        } elseif ($drivetrains->contains('hybrid')) {
            $spec = 'Hybrid';
        } else {
            $spec = 'Traditional';
        }

        $location = $activeCars->pluck('location')->filter()->first() ?? 'Nepal';

        $businessNews = \App\Models\BusinessNews::where('user_id', $user->id)
            ->where('is_published', true)
            ->with('newscategory')
            ->latest()
            ->get();

        // ── Banner ads between car grid and reviews (priority DESC) ──────
        $businessProfileAds = Advertisement::liveForPlacement('business_profile')->get();

        return view('frontend.pages.business_profile', compact(
            'user',
            'activeCars',
            'allReviews',
            'avgRating',
            'reviewCount',
            'businessName',
            'contact',
            'email',
            'mapLocation',
            'spec',
            'location',
            'businessNews',
            'businessProfileAds'
        ));
    }
}