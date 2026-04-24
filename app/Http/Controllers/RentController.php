<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentController extends Controller
{
    // ── Shared base query ─────────────────────────────────────────────
    // Only cars where listing_type is 'rent' or 'both', and status is
    // 'available'. Upcoming/preorder cars are excluded from the rent page
    // because a renter needs a physical car to pick up.

    private function baseQuery()
    {
        return Car::with(['seller', 'primaryImage'])
            ->whereIn('listing_type', ['rent', 'both'])
            ->where('status', 'available');
    }

    // ── Public index ──────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = $this->baseQuery()->latest();

        $this->applyFilters($query, $request);

        $cars = $query->paginate(9)->withQueryString();

        // Sidebar filter options — scoped to rentable cars only
        $locations   = $this->baseQuery()->distinct()->pluck('location')->sort()->values();
        $totalActive = $this->baseQuery()->count();
        $brands      = $this->baseQuery()->distinct()->orderBy('brand')->pluck('brand');
        $models      = $this->baseQuery()->distinct()->orderBy('model')->pluck('model');
        $minYear     = (int) ($this->baseQuery()->min('year') ?? date('Y'));
        $maxYear     = (int) ($this->baseQuery()->max('year') ?? date('Y'));

        // Price range filters operate on rent_price_per_day, not sale price
        $minRentPrice = (int) ($this->baseQuery()->min('rent_price_per_day') ?? 0);
        $maxRentPrice = (int) ($this->baseQuery()->max('rent_price_per_day') ?? 50000);

        // Track which cars the logged-in buyer already has an active rental for,
        // so the view can show "Already booked" instead of "Rent Now".
        $rentedCarIds = collect();

        if (Auth::check() && Auth::user()->hasRole('buyer')) {
            $carIds = $cars->pluck('id');

            $rentedCarIds = CarRental::where('renter_id', Auth::id())
                ->whereIn('car_id', $carIds)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->pluck('car_id');
        }

        $rentAds = \App\Models\Advertisement::with('car')
            ->where('placement', 'rent')
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->get();

        return view('frontend.pages.rent', compact(
            'cars',
            'locations',
            'totalActive',
            'brands',
            'models',
            'minYear',
            'maxYear',
            'minRentPrice',
            'maxRentPrice',
            'rentedCarIds',
            'rentAds'
        ));
    }

    // ── JSON search endpoint (called by Alpine/fetch on filter change) ─

    public function search(Request $request)
    {
        $query = $this->baseQuery()->latest();

        $this->applyFilters($query, $request);

        $paginator = $query->paginate(9)->withQueryString();

        // Track active rentals for the logged-in buyer
        $rentedCarIds = collect();

        if (Auth::check() && Auth::user()->hasRole('buyer')) {
            $carIds = $paginator->pluck('id');

            $rentedCarIds = CarRental::where('renter_id', Auth::id())
                ->whereIn('car_id', $carIds)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->pluck('car_id');
        }

        $cars = $paginator->map(function ($car) use ($rentedCarIds) {
            return [
                'id'               => $car->id,
                'name'             => $car->displayName(),
                'location'         => $car->location,
                'drivetrain'       => $car->drivetrain,
                'condition'        => ucfirst($car->condition),
                'mileage'          => number_format($car->mileage),
                'rent_price'       => number_format($car->rent_price_per_day),
                'rent_min_days'    => $car->rent_min_days ?? 1,
                'rent_max_days'    => $car->rent_max_days,
                'duration_label'   => $car->rentDurationLabel(),
                'deposit'          => $car->rent_deposit ? number_format($car->rent_deposit) : null,
                'primary_image'    => $car->primary_image ? asset('storage/' . $car->primary_image) : null,
                'url'              => route('cars.show', $car->id),
                'seller_role'      => $car->seller?->getRoleNames()->first(),
                'already_rented'   => $rentedCarIds->contains($car->id),
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

    // ── Filter logic ──────────────────────────────────────────────────

    private function applyFilters($query, Request $request): void
    {
        // Full-text / keyword search on brand, model, variant
        if ($request->filled('search')) {
            $s = trim($request->search);

            if (mb_strlen($s) >= 3) {
                $query->whereRaw(
                    'MATCH(brand, model, variant) AGAINST(? IN BOOLEAN MODE)',
                    ['+' . implode('* +', explode(' ', preg_replace('/\s+/', ' ', $s))) . '*']
                );
            } else {
                $query->where(fn ($q) => $q
                    ->where('brand', 'like', "%$s%")
                    ->orWhere('model', 'like', "%$s%")
                    ->orWhere('variant', 'like', "%$s%")
                );
            }
        }

        // Standard filters shared with marketplace
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

        // Rent-specific price filter — operates on rent_price_per_day
        if ($request->filled('rent_price_min')) {
            $query->where('rent_price_per_day', '>=', $request->rent_price_min);
        }
        if ($request->filled('rent_price_max')) {
            $query->where('rent_price_per_day', '<=', $request->rent_price_max);
        }

        // Min/max days filter — show only cars whose duration range fits what the buyer wants
        if ($request->filled('min_days')) {
            $query->where(fn ($q) => $q
                ->whereNull('rent_max_days')
                ->orWhere('rent_max_days', '>=', $request->min_days)
            );
        }

        // Sort
        match ($request->sort) {
            'price_asc'  => $query->reorder()->orderBy('rent_price_per_day', 'asc'),
            'price_desc' => $query->reorder()->orderBy('rent_price_per_day', 'desc'),
            'newest'     => $query->reorder()->latest(),
            default      => null,
        };
    }
}