<?php

namespace App\Http\Controllers;

use App\Models\EvListing;
use Illuminate\Http\Request;

class EvListingController extends Controller
{
    public function index(Request $request)
    {
        $query = EvListing::query();

        if ($brand = $request->get('brand')) {
            $query->where('brand', $brand);
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', (int) $minPrice);
        }

        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        match ($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->orderByDesc('created_at'),
        };

        $listings = $query->paginate(12)->withQueryString();

        $brands = EvListing::query()->select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('frontend.pages.ev_prices.index', compact('listings', 'brands'));
    }

    public function show(EvListing $evListing)
    {
        $similar = EvListing::where('brand', $evListing->brand)
            ->where('id', '!=', $evListing->id)
            ->limit(4)
            ->get();

        return view('frontend.pages.ev_prices.show', [
            'listing' => $evListing,
            'similar' => $similar,
        ]);
    }
}