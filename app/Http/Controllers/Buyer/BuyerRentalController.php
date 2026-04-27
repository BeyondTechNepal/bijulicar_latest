<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Mail\RentalBookingReceivedMail;
use App\Mail\RentalCancelledMail;
use App\Models\Car;
use App\Models\CarRental;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BuyerRentalController extends Controller
{
    // ── List all rentals for the logged-in buyer ──────────────────────

    public function index()
    {
        $rentals = Auth::user()
            ->rentalBookings()
            ->with([
                'car'     => fn ($q) => $q->withTrashed(),
                'reviews' => fn ($q) => $q->where('buyer_id', Auth::id()),
            ])
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.rentals.index', compact('rentals'));
    }

    // ── Show a single rental booking ──────────────────────────────────

    public function show(CarRental $carRental)
    {
        abort_if($carRental->renter_id != Auth::id(), 403);

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

        $renterId = Auth::id();
        $pickup   = \Carbon\Carbon::parse($request->pickup_date);
        $return   = \Carbon\Carbon::parse($request->return_date);
        $days     = (int) $pickup->diffInDays($return);

        // ── Date min/max validation (no DB needed, done before transaction) ──

        // We need the car for min/max check — a plain find is fine here
        // since we'll re-fetch with a lock inside the transaction.
        $carForValidation = Car::findOrFail($request->car_id);

        if ($carForValidation->rent_min_days && $days < $carForValidation->rent_min_days) {
            return back()
                ->withInput()
                ->withErrors(['return_date' => "Minimum rental duration for this car is {$carForValidation->rent_min_days} days."]);
        }

        if ($carForValidation->rent_max_days && $days > $carForValidation->rent_max_days) {
            return back()
                ->withInput()
                ->withErrors(['return_date' => "Maximum rental duration for this car is {$carForValidation->rent_max_days} days."]);
        }

        // ── Atomic availability check + booking creation ──────────────

        $rental = DB::transaction(function () use ($request, $renterId, $pickup, $return, $days) {

            // Lock the car row — concurrent requests for the same car
            // will queue here until this transaction commits or rolls back.
            $car = Car::lockForUpdate()->findOrFail($request->car_id);

            // ── Guard checks (re-evaluated inside the lock) ───────────

            abort_if(!$car->isRentable(),  422, 'This car is not available for rent.');
            abort_if(!$car->isAvailable(), 422, 'This car is currently unavailable.');
            abort_if($car->seller_id === $renterId, 422, 'You cannot rent your own listing.');

            // Block if ALL stock units are currently out on confirmed/active rental
            abort_if(
                $car->availableStockForRent() === 0,
                422,
                'All units of this car are currently out on rental. Please try again once a rental ends.'
            );

            // Prevent duplicate active bookings by the same buyer on the same car
            $hasActiveRental = CarRental::where('renter_id', $renterId)
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->exists();

            abort_if($hasActiveRental, 422, 'You already have an active rental booking for this car.');

            // ── Create the booking ────────────────────────────────────

            return CarRental::create([
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
        });

        // ── Notify & email the owner (outside transaction — no DB lock needed) ──

        $rental->load('owner');

        app(NotificationService::class)->rentalBookingReceived($rental);

        if ($rental->owner?->email) {
            Mail::to($rental->owner->email)
                ->queue(new RentalBookingReceivedMail($rental));
        }

        return redirect()
            ->route('buyer.rentals.show', $rental->id)
            ->with('success', 'Rental booking submitted! The owner will confirm your dates shortly.');
    }

    // ── Cancel a booking (only from pending or confirmed) ─────────────

    public function cancel(CarRental $carRental)
    {
        abort_if($carRental->renter_id != Auth::id(), 403);
        abort_if(!$carRental->isCancellable(), 422, 'This booking can no longer be cancelled.');

        $carRental->update([
            'status'              => 'cancelled',
            'cancelled_by'        => 'renter',
            'cancellation_reason' => 'Cancelled by renter.',
        ]);

        // ── Notify & email the owner ──────────────────────────────────

        $carRental->load('owner');

        app(NotificationService::class)->rentalCancelled($carRental, 'renter');

        if ($carRental->owner?->email) {
            Mail::to($carRental->owner->email)
                ->queue(new RentalCancelledMail($carRental, 'renter', $carRental->owner->name));
        }

        return redirect()
            ->route('buyer.rentals.index')
            ->with('success', 'Rental booking cancelled successfully.');
    }
}