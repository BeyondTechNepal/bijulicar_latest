<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\HomeController;

class SellerCarController extends Controller
{
    /** Detect which role is using this controller and return the correct route prefix and layout. */
    private function context(): array
    {
        if (Auth::user()->hasRole('business')) {
            return ['prefix' => 'business', 'layout' => 'dashboard.business.layout'];
        }
        return ['prefix' => 'seller', 'layout' => 'dashboard.seller.layout'];
    }

    /**
     * Show all listings by this seller.
     */
    public function index()
    {
        $cars = Auth::user()
            ->listedCars()
            ->withCount('orders')
            ->with('primaryImage')
            ->latest()
            ->paginate(10);

        return view('dashboard.seller.cars.index', array_merge(compact('cars'), $this->context()));
    }

    /**
     * Show the create listing form.
     */
    public function create()
    {
        return view('dashboard.seller.cars.create', $this->context());
    }

    /**
     * Save a new listing.
     */
    public function store(Request $request)
    {
        $ctx = $this->context();

        $request->validate([
            'brand'            => ['required', 'string', 'max:100'],
            'model'            => ['required', 'string', 'max:100'],
            'year'             => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'variant'          => ['nullable', 'string', 'max:100'],
            'drivetrain'       => ['required', 'in:ev,hybrid,petrol,diesel'],
            'mileage'          => ['required', 'integer', 'min:0'],
            'range_km'         => ['nullable', 'integer', 'min:0'],
            'battery_kwh'      => ['nullable', 'numeric', 'min:0'],
            'color'            => ['nullable', 'string', 'max:50'],
            'condition'        => ['required', 'in:new,used,certified'],
            'price'            => ['required', 'integer', 'min:1'],
            'price_negotiable' => ['boolean'],
            'location'         => ['required', 'string', 'max:100'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'stock_quantity'   => ['required', 'integer', 'min:1', 'max:1000'],
            'images'           => ['nullable', 'array', 'max:8'],
            'images.*'         => ['image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'is_preorder'           => ['boolean'],
            'expected_arrival_date' => ['nullable', 'required_if:is_preorder,1', 'date', 'after:today'],
            'preorder_deposit'      => ['nullable', 'required_if:is_preorder,1', 'integer', 'min:1000'],
        ]);

        $car = Car::create([
            'seller_id'        => Auth::id(),
            'brand'            => $request->brand,
            'model'            => $request->model,
            'year'             => $request->year,
            'variant'          => $request->variant,
            'drivetrain'       => $request->drivetrain,
            'mileage'          => $request->mileage,
            'range_km'         => $request->range_km,
            'battery_kwh'      => $request->battery_kwh,
            'color'            => $request->color,
            'condition'        => $request->condition,
            'price'            => $request->price,
            'price_negotiable' => $request->boolean('price_negotiable'),
            'location'         => $request->location,
            'description'      => $request->description,
            'status'           => $request->boolean('is_preorder') ? 'upcoming' : 'available',
            'stock_quantity'   => $request->stock_quantity,
            'is_preorder'           => $request->boolean('is_preorder'),
            'expected_arrival_date' => $request->boolean('is_preorder') ? $request->expected_arrival_date : null,
            'preorder_deposit'      => $request->boolean('is_preorder') ? $request->preorder_deposit : null,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('car-images', 'public');

                CarImage::create([
                    'car_id'     => $car->id,
                    'path'       => $path,
                    'alt'        => $car->displayName(),
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);

                if ($index === 0) {
                    $car->update(['primary_image' => $path]);
                }
            }
        }


        // Bust homepage caches
        Cache::forget(HomeController::CACHE_FLEET_COUNTS);
        Cache::forget(HomeController::CACHE_RECENT_CARS);
        Cache::forget(HomeController::CACHE_FEATURED_BIZ);
        return redirect()
            ->route($ctx['prefix'] . '.cars.index')
            ->with('success', "{$car->displayName()} listed successfully with {$car->stock_quantity} unit(s).");
    }

    /**
     * Show the edit form for a listing.
     */
    public function edit(Car $car)
    {
        abort_if($car->seller_id != Auth::id(), 403);

        $car->load('images');

        return view('dashboard.seller.cars.edit', array_merge(compact('car'), $this->context()));
    }

    /**
     * Update an existing listing.
     */
    public function update(Request $request, Car $car)
    {
        abort_if($car->seller_id != Auth::id(), 403);

        $ctx = $this->context();

        $request->validate([
            'brand'            => ['required', 'string', 'max:100'],
            'model'            => ['required', 'string', 'max:100'],
            'year'             => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'variant'          => ['nullable', 'string', 'max:100'],
            'drivetrain'       => ['required', 'in:ev,hybrid,petrol,diesel'],
            'mileage'          => ['required', 'integer', 'min:0'],
            'range_km'         => ['nullable', 'integer', 'min:0'],
            'battery_kwh'      => ['nullable', 'numeric', 'min:0'],
            'color'            => ['nullable', 'string', 'max:50'],
            'condition'        => ['required', 'in:new,used,certified'],
            'price'            => ['required', 'integer', 'min:1'],
            'price_negotiable' => ['boolean'],
            'location'         => ['required', 'string', 'max:100'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'status'           => ['required', 'in:available,reserved,inactive,upcoming'],
            'stock_quantity'   => ['required', 'integer', 'min:0', 'max:1000'],
            'new_images'       => ['nullable', 'array', 'max:8'],
            'new_images.*'     => ['image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_images'    => ['nullable', 'array'],
            'remove_images.*'  => ['exists:car_images,id'],
            'is_preorder'           => ['boolean'],
            'expected_arrival_date' => ['nullable', 'required_if:is_preorder,1', 'date'],
            'preorder_deposit'      => ['nullable', 'required_if:is_preorder,1', 'integer', 'min:1000'],
        ]);

        // Determine correct status
        $newStatus = $request->status;
        if ($request->boolean('is_preorder')) {
            $newStatus = 'upcoming';
        } elseif ($request->stock_quantity > 0 && $car->status === 'sold') {
            $newStatus = 'available';
        }

        $car->update([
            'brand'            => $request->brand,
            'model'            => $request->model,
            'year'             => $request->year,
            'variant'          => $request->variant,
            'drivetrain'       => $request->drivetrain,
            'mileage'          => $request->mileage,
            'range_km'         => $request->range_km,
            'battery_kwh'      => $request->battery_kwh,
            'color'            => $request->color,
            'condition'        => $request->condition,
            'price'            => $request->price,
            'price_negotiable' => $request->boolean('price_negotiable'),
            'location'         => $request->location,
            'description'      => $request->description,
            'status'           => $newStatus,
            'stock_quantity'   => $request->stock_quantity,
            'is_preorder'           => $request->boolean('is_preorder'),
            'expected_arrival_date' => $request->boolean('is_preorder') ? $request->expected_arrival_date : null,
            'preorder_deposit'      => $request->boolean('is_preorder') ? $request->preorder_deposit : null,
        ]);

        // Remove selected images
        if ($request->filled('remove_images')) {
            $car->images()
                ->whereIn('id', $request->remove_images)
                ->each(fn($img) => $img->delete());
        }

        // Upload new images
        if ($request->hasFile('new_images')) {
            $currentMax = $car->images()->max('sort_order') ?? -1;

            foreach ($request->file('new_images') as $index => $file) {
                $path = $file->store('car-images', 'public');

                CarImage::create([
                    'car_id'     => $car->id,
                    'path'       => $path,
                    'alt'        => $car->displayName(),
                    'sort_order' => $currentMax + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        // Re-sync primary image
        $firstImage = $car->images()->orderBy('sort_order')->first();
        $car->update(['primary_image' => $firstImage?->path]);
        if ($firstImage) {
            $car->images()->update(['is_primary' => false]);
            $firstImage->update(['is_primary' => true]);
        }


        // Bust homepage caches
        Cache::forget(HomeController::CACHE_FLEET_COUNTS);
        Cache::forget(HomeController::CACHE_RECENT_CARS);
        Cache::forget(HomeController::CACHE_FEATURED_BIZ);
        return redirect()
            ->route($ctx['prefix'] . '.cars.index')
            ->with('success', "Listing updated. Stock: {$car->fresh()->stock_quantity} unit(s).");
    }

    /**
     * Soft-delete a listing. Images are kept so that existing orders
     * can still reference them. Use forceDelete() to permanently remove.
     */
    public function destroy(Car $car)
    {
        abort_if($car->seller_id != Auth::id(), 403);

        $hasActiveOrders = $car->orders()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        abort_if($hasActiveOrders, 422, 'Cannot delete a listing with active orders. Cancel the orders first.');

        $name = $car->displayName();

        // Soft-delete: sets deleted_at, keeps images & orders intact.
        $car->delete();

        $ctx = $this->context();

        // Bust homepage caches — deleted listing affects counts, recent cars, and rankings
        Cache::forget(HomeController::CACHE_FLEET_COUNTS);
        Cache::forget(HomeController::CACHE_RECENT_CARS);
        Cache::forget(HomeController::CACHE_FEATURED_BIZ);

        return redirect()
            ->route($ctx['prefix'] . '.cars.index')
            ->with('success', "{$name} listing deleted.");
    }
}