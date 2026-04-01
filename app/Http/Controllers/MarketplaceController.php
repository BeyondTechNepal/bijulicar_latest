<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Order;
use App\Models\PreOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    /** JSON endpoint for the live search — no page reload needed */
    public function search(Request $request)
    {
        $query = Car::with(['seller', 'primaryImage'])
            ->whereIn('status', ['available', 'upcoming'])
            ->latest();

        $this->applyFilters($query, $request);

        $paginator = $query->paginate(9)->withQueryString();

        // Collect buyer order/preorder state for this page of results
        $orderedCarIds    = collect();
        $preOrderedCarIds = collect();

        if (Auth::check() && Auth::user()->hasRole('buyer')) {
            $carIds = $paginator->pluck('id');

            $orderedCarIds = Order::where('buyer_id', Auth::id())
                ->whereIn('car_id', $carIds)
                ->whereIn('status', ['pending', 'confirmed', 'completed'])
                ->pluck('car_id');

            $preOrderedCarIds = PreOrder::where('buyer_id', Auth::id())
                ->whereIn('car_id', $carIds)
                ->whereIn('status', ['pending_deposit', 'deposit_paid'])
                ->pluck('car_id');
        }

        $cars = $paginator->map(function ($car) use ($orderedCarIds, $preOrderedCarIds) {
            $img = $car->primary_image
                ? asset('storage/' . $car->primary_image)
                : null;

            return [
                'id'                    => $car->id,
                'name'                  => $car->displayName(),
                'location'              => $car->location,
                'drivetrain'            => $car->drivetrain,
                'condition'             => ucfirst($car->condition),
                'mileage'               => number_format($car->mileage),
                'range_km'              => $car->range_km,
                'battery_kwh'           => $car->battery_kwh,
                'price'                 => number_format($car->price),
                'price_negotiable'      => $car->price_negotiable,
                'primary_image'         => $img,
                'url'                   => route('cars.show', $car->id),
                'order_url'             => route('cars.show', $car->id) . '#place-order',
                'seller_role'           => $car->seller?->getRoleNames()->first(),
                'is_preorder'           => (bool) $car->is_preorder,
                'expected_arrival_date' => $car->expected_arrival_date?->format('M Y'),
                'preorder_deposit'      => $car->preorder_deposit ? number_format($car->preorder_deposit) : null,
                'already_ordered'       => $orderedCarIds->contains($car->id),
                'already_pre_ordered'   => $preOrderedCarIds->contains($car->id),
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
        if ($request->filled('price_min')) {
            $query->where(fn($q) => $q->where('price', '>=', $request->price_min)->orWhere('status', 'upcoming'));
        }
        if ($request->filled('price_max')) {
            $query->where(fn($q) => $q->where('price', '<=', $request->price_max)->orWhere('status', 'upcoming'));
        }
        if ($request->boolean('only_available')) { $query->where('status', 'available'); }
        match ($request->sort) {
            'price_asc'  => $query->reorder()->orderBy('price', 'asc'),
            'price_desc' => $query->reorder()->orderBy('price', 'desc'),
            default      => null,
        };
    }

    public function index(Request $request)
    {
        $activeStatuses = ['available', 'upcoming'];

        $query = Car::with('seller')
            ->whereIn('status', $activeStatuses)
            ->latest();

        $this->applyFilters($query, $request);

        $cars        = $query->paginate(9)->withQueryString();
        $locations   = Car::whereIn('status', $activeStatuses)->distinct()->pluck('location')->sort()->values();
        $totalActive = Car::whereIn('status', $activeStatuses)->count();

        $brands   = Car::whereIn('status', $activeStatuses)->distinct()->orderBy('brand')->pluck('brand');
        $models   = Car::whereIn('status', $activeStatuses)->distinct()->orderBy('model')->pluck('model');
        $minYear  = (int) (Car::whereIn('status', $activeStatuses)->min('year') ?? date('Y'));
        $maxYear  = (int) (Car::whereIn('status', $activeStatuses)->max('year') ?? date('Y'));
        $minPrice = (int) (Car::whereIn('status', $activeStatuses)->min('price') ?? 0);
        $maxPrice = (int) (Car::whereIn('status', $activeStatuses)->max('price') ?? 10000000);

        $marketplaceAds = \App\Models\Advertisement::with('car')
            ->where('placement', 'marketplace')
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->get();

        // Collect buyer order/preorder state for the initial server-rendered page
        $orderedCarIds    = collect();
        $preOrderedCarIds = collect();

        if (Auth::check() && Auth::user()->hasRole('buyer')) {
            $carIds = $cars->pluck('id');

            $orderedCarIds = Order::where('buyer_id', Auth::id())
                ->whereIn('car_id', $carIds)
                ->whereIn('status', ['pending', 'confirmed', 'completed'])
                ->pluck('car_id');

            $preOrderedCarIds = PreOrder::where('buyer_id', Auth::id())
                ->whereIn('car_id', $carIds)
                ->whereIn('status', ['pending_deposit', 'deposit_paid'])
                ->pluck('car_id');
        }

        return view('frontend.pages.marketplace', compact(
            'cars', 'locations', 'totalActive', 'marketplaceAds',
            'brands', 'models', 'minYear', 'maxYear', 'minPrice', 'maxPrice',
            'orderedCarIds', 'preOrderedCarIds'
        ));
    }
}