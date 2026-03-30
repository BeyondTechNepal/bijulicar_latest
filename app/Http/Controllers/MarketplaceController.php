<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /** JSON endpoint for the live search — no page reload needed */
    public function search(Request $request)
    {
        $query = Car::with(['seller', 'primaryImage'])
            ->where('status', 'available')
            ->latest();

        $this->applyFilters($query, $request);

        $paginator = $query->paginate(9)->withQueryString();

        $cars = $paginator->map(function ($car) {
            $img = $car->primary_image
                ? asset('storage/' . $car->primary_image)
                : null;

            return [
                'id'               => $car->id,
                'name'             => $car->displayName(),
                'location'         => $car->location,
                'drivetrain'       => $car->drivetrain,
                'condition'        => ucfirst($car->condition),
                'mileage'          => number_format($car->mileage),
                'range_km'         => $car->range_km,
                'battery_kwh'      => $car->battery_kwh,
                'price'            => number_format($car->price),
                'price_negotiable' => $car->price_negotiable,
                'primary_image'    => $img,
                'url'              => route('cars.show', $car->id),
                'seller_role'      => $car->seller?->getRoleNames()->first(),
            ];
        });

        return response()->json([
            'cars'         => $cars,
            'total'        => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'per_page'     => $paginator->perPage(),
        ]);
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('brand', 'like', "%$s%")->orWhere('model', 'like', "%$s%"));
        }
        if ($request->filled('drivetrain') && $request->drivetrain !== 'all') {
            $request->drivetrain === 'classic'
                ? $query->whereIn('drivetrain', ['petrol', 'diesel'])
                : $query->where('drivetrain', $request->drivetrain);
        }
        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        if ($request->filled('brand'))      { $query->where('brand', 'like', '%' . $request->brand . '%'); }
        if ($request->filled('model_name')) { $query->where('model', 'like', '%' . $request->model_name . '%'); }
        if ($request->filled('year_from'))  { $query->where('year', '>=', $request->year_from); }
        if ($request->filled('year_to'))    { $query->where('year', '<=', $request->year_to); }
        if ($request->filled('price_min'))  { $query->where('price', '>=', $request->price_min); }
        if ($request->filled('price_max'))  { $query->where('price', '<=', $request->price_max); }
        if ($request->boolean('only_available')) { $query->where('status', 'available'); }
        match ($request->sort) {
            'price_asc'  => $query->reorder()->orderBy('price', 'asc'),
            'price_desc' => $query->reorder()->orderBy('price', 'desc'),
            default      => null,
        };
    }

    public function index(Request $request)
    {
        $query = Car::with('seller')
            ->where('status', 'available')
            ->latest();

        $this->applyFilters($query, $request);

        $cars      = $query->paginate(9)->withQueryString();
        $locations = Car::where('status', 'available')->distinct()->pluck('location')->sort()->values();
        $totalActive = Car::where('status', 'available')->count();

        // Data for smart suggestions & range controls
        $brands   = Car::where('status', 'available')->distinct()->orderBy('brand')->pluck('brand');
        $models   = Car::where('status', 'available')->distinct()->orderBy('model')->pluck('model');
        $minYear  = (int) (Car::where('status', 'available')->min('year') ?? date('Y'));
        $maxYear  = (int) (Car::where('status', 'available')->max('year') ?? date('Y'));
        $minPrice = (int) (Car::where('status', 'available')->min('price') ?? 0);
        $maxPrice = (int) (Car::where('status', 'available')->max('price') ?? 10000000);

        // Active marketplace ads
        $marketplaceAds = \App\Models\Advertisement::with('car')
            ->where('placement', 'marketplace')
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->get();

        return view('frontend.pages.marketplace', compact(
            'cars', 'locations', 'totalActive', 'marketplaceAds',
            'brands', 'models', 'minYear', 'maxYear', 'minPrice', 'maxPrice'
        ));
    }
}