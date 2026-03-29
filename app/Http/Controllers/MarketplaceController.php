<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with('seller')
            ->where('status', 'available')
            ->latest();

        // Quick search bar (brand or model)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Drivetrain dropdown
        if ($request->filled('drivetrain') && $request->drivetrain !== 'all') {
            if ($request->drivetrain === 'classic') {
                $query->whereIn('drivetrain', ['petrol', 'diesel']);
            } else {
                $query->where('drivetrain', $request->drivetrain);
            }
        }

        // Location dropdown
        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('location', $request->location);
        }

        // Advanced: brand
        if ($request->filled('brand')) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }

        // Advanced: model
        if ($request->filled('model_name')) {
            $query->where('model', 'like', "%{$request->model_name}%");
        }

        // Advanced: year range
        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }

        // Advanced: price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Only available toggle
        if ($request->boolean('only_available')) {
            $query->where('status', 'available');
        }

        // Sort
        if ($request->sort === 'price_asc') {
            $query->reorder()->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->reorder()->orderBy('price', 'desc');
        }

        $cars      = $query->paginate(9)->withQueryString();
        $locations = Car::where('status', 'available')->distinct()->pluck('location')->sort()->values();
        $totalActive = Car::where('status', 'available')->count();

        // Active marketplace ads
        $marketplaceAds = \App\Models\Advertisement::with('car')
            ->where('placement', 'marketplace')
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->get();

        return view('frontend.pages.marketplace', compact('cars', 'locations', 'totalActive', 'marketplaceAds'));
    }
}