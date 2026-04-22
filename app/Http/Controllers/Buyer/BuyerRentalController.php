<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerRentalController extends Controller
{
    // ── List all rentals for the logged-in buyer ──────────────────────

    public function index()
    {
        $rentals = Auth::user()
            ->rentalBookings()
            ->with(['car' => fn ($q) => $q->withTrashed()])
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.rentals.index', compact('rentals'));
    }

    // ── Show a single rental booking ──────────────────────────────────

    public function show(CarRental $carRental)
    {
        abort_if($carRental->renter_id !== Auth::id(), 403);

        $carRental->load(['car' => fn ($q) => $q->withTrashed(), 'owner']);

        return view('dashboard.buyer.rentals.show', compact('carRental'));
    }

    // ── Submit a new rental booking ───────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'car_id'       => ['required', 'exists:cars,id'],
            'pickup_date'  => ['required', 'date', 'after_or_equal:today'],
            'return_date'  => ['required', 'date', 'after:pickup_date'],
            'renter_name'  => ['required', 'string', 'max:100'],
            'renter_phone' => ['required', 'string', 'max:20'],
            'renter_email' => ['required', 'email', 'max:255'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $car     = Car::findOrFail($request->car_id);
        $renterId = Auth::id();

        // ── Guard checks ──────────────────────────────────────────────

        abort_if(!$car->isRentable(),   422, 'This car is not available for rent.');
        abort_if(!$car->isAvailable(),  422, 'This car is currently unavailable.');
        abort_if($car->seller_id === $renterId, 422, 'You cannot rent your own listing.');

        // Prevent duplicate active bookings on the same car
        $hasActiveRental = CarRental::where('renter_id', $renterId)
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->exists();

        abort_if($hasActiveRental, 422, 'You already have an active rental booking for this car.');

        // ── Date maths ────────────────────────────────────────────────

        $pickup  = \Carbon\Carbon::parse($request->pickup_date);
        $return  = \Carbon\Carbon::parse($request->return_date);
        $days    = (int) $pickup->diffInDays($return);

        // Validate against car's min/max day constraints
        if ($car->rent_min_days && $days < $car->rent_min_days) {
            return back()
                ->withInput()
                ->withErrors(['return_date' => "Minimum rental duration for this car is {$car->rent_min_days} days."]);
        }

        if ($car->rent_max_days && $days > $car->rent_max_days) {
            return back()
                ->withInput()
                ->withErrors(['return_date' => "Maximum rental duration for this car is {$car->rent_max_days} days."]);
        }

        // ── Create the booking ────────────────────────────────────────

        $rental = CarRental::create([
            'car_id'            => $car->id,
            'car_snapshot_name' => $car->displayName(),
            'renter_id'         => $renterId,
            'owner_id'          => $car->seller_id,
            'pickup_date'       => $request->pickup_date,
            'return_date'       => $request->return_date,
            'total_days'        => $days,
            'price_per_day'     => $car->rent_price_per_day,
            'deposit_amount'    => $car->rent_deposit,
            'total_price'       => $car->rent_price_per_day * $days,
            'status'            => 'pending',
            'renter_name'       => $request->renter_name,
            'renter_phone'      => $request->renter_phone,
            'renter_email'      => $request->renter_email,
            'notes'             => $request->notes,
        ]);

        return redirect()
            ->route('buyer.rentals.show', $rental->id)
            ->with('success', 'Rental booking submitted! The owner will confirm your dates shortly.');
    }

    // ── Cancel a booking (only from pending or confirmed) ─────────────

    public function cancel(CarRental $carRental)
    {
        abort_if($carRental->renter_id !== Auth::id(), 403);
        abort_if(!$carRental->isCancellable(), 422, 'This booking can no longer be cancelled.');

        $carRental->update([
            'status'              => 'cancelled',
            'cancelled_by'        => 'renter',
            'cancellation_reason' => 'Cancelled by renter.',
        ]);

        return redirect()
            ->route('buyer.rentals.index')
            ->with('success', 'Rental booking cancelled successfully.');
    }
}