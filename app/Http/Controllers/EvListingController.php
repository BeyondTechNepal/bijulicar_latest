<?php

namespace App\Http\Controllers;

use App\Models\EvListing;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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

        // Pull every matching variant row, then fold them into one
        // "card" per brand+model so Dynamic/Premium/etc. become
        // toggleable variants on a single card instead of separate cards.
        $all = $query->orderBy('brand')->orderBy('model')->orderBy('price')->get();

        $groups = $all
            ->groupBy(fn ($ev) => $ev->brand . '|' . $ev->model)
            ->map(function ($variants) {
                return (object) [
                    'brand'     => $variants->first()->brand,
                    'model'     => $variants->first()->model,
                    'variants'  => $variants->values(),
                    'min_price' => $variants->min('price'),
                ];
            })
            ->values();

        $groups = match ($request->get('sort')) {
            'price_asc'  => $groups->sortBy('min_price')->values(),
            'price_desc' => $groups->sortByDesc('min_price')->values(),
            default      => $groups->sortBy(fn ($g) => $g->brand . $g->model)->values(),
        };

        // Manually paginate the grouped collection (12 distinct
        // cars per page), preserving the current query string.
        $perPage = 12;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $listings = new LengthAwarePaginator(
            $groups->forPage($page, $perPage)->values(),
            $groups->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

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