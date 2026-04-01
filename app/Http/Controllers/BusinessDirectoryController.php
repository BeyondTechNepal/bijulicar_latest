<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Car;
use App\Models\News;
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

        // Sort
        $sort = $request->get('sort', 'listings');
        $businesses = $query->get()->map(function ($user) {
            $activeCars    = $user->listedCars->whereIn('status', ['available', 'upcoming']);
            $allReviews    = Review::where('seller_id', $user->id)->get();
            $avgRating     = $allReviews->avg('rating') ?? 0;
            $reviewCount   = $allReviews->count();

            // Determine specialization label from listings
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

            // Determine a representative location from their listings
            $location = $activeCars->pluck('location')->filter()->first() ?? 'Nepal';

            return [
                'id'           => $user->id,
                'name'         => $user->businessVerification->business_name ?? $user->name,
                'contact'      => $user->businessVerification->contact ?? null,
                'initials'     => strtoupper(substr($user->businessVerification->business_name ?? $user->name, 0, 2)),
                'active_listings' => $activeCars->count(),
                'avg_rating'   => round($avgRating, 1),
                'review_count' => $reviewCount,
                'specialization' => $spec,
                'location'     => $location,
                'profile_url'  => route('businesses.show', $user->id),
                'user'         => $user,
            ];
        });

        // Apply sort after map
        if ($sort === 'rating') {
            $businesses = $businesses->sortByDesc('avg_rating');
        } elseif ($sort === 'reviews') {
            $businesses = $businesses->sortByDesc('review_count');
        } else {
            $businesses = $businesses->sortByDesc('active_listings');
        }

        // Stats bar
        $totalBusinesses = $businesses->count();
        $totalListings   = $businesses->sum('active_listings');
        $totalCities     = Car::whereIn('status', ['available', 'upcoming'])
                              ->pluck('location')
                              ->map(fn($l) => explode(',', $l)[0])
                              ->unique()
                              ->count();

        // Latest business news (from NewsArticle by sellers with business role)
        // Using existing News model (admin news) as fallback — news by businesses
        // is tied to cars/sellers. We fetch latest news for sidebar.
        $latestNews = \App\Models\BusinessNews::where('is_published', true)
            ->with(['business.businessVerification', 'newscategory'])
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.pages.businesses', compact(
            'businesses',
            'totalBusinesses',
            'totalListings',
            'totalCities',
            'latestNews'
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
 
        // Specialization
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
 
        // ── NEW: fetch published news articles by this business ──────────
        $businessNews = \App\Models\BusinessNews::where('user_id', $user->id)
            ->where('is_published', true)
            ->with('newscategory')
            ->latest()
            ->get();
 
        return view('frontend.pages.business_profile', compact(
            'user',
            'activeCars',
            'allReviews',
            'avgRating',
            'reviewCount',
            'businessName',
            'contact',
            'spec',
            'location',
            'businessNews',    
        ));
    }
}