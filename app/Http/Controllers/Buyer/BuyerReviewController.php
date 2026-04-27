<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\Car;
use App\Models\CarRental;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BuyerReviewController extends Controller
{
    /**
     * Show all reviews written by the logged-in buyer.
     */
    public function index()
    {
        $reviews = Auth::user()
            ->reviews()
            ->with(['car' => fn($q) => $q->withTrashed(), 'carRental'])
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.reviews.index', compact('reviews'));
    }

    /**
     * Show the form to write a new review.
     *
     * Accepts either:
     *   GET /reviews/create?car_id={id}      → purchase review
     *   GET /reviews/create?rental_id={id}   → rental review
     */
    public function create(Request $request)
    {
        // ── Rental review ─────────────────────────────────────────────
        if ($request->filled('rental_id')) {
            $rental = CarRental::findOrFail($request->rental_id);

            abort_if($rental->renter_id != Auth::id(), 403);
            abort_if(!$rental->isCompleted(), 403, 'You can only review a rental after it has been completed.');

            $car = Car::withTrashed()->findOrFail($rental->car_id);

            abort_if($car->trashed(), 403, 'This listing has been removed and can no longer be reviewed.');

            $alreadyReviewed = Review::where('buyer_id', Auth::id())
                ->where('car_rental_id', $rental->id)
                ->exists();

            abort_if($alreadyReviewed, 422, 'You have already reviewed this rental.');

            return view('dashboard.buyer.reviews.create', [
                'car'    => $car,
                'rental' => $rental,
            ]);
        }

        // ── Purchase review ───────────────────────────────────────────
        $car = Car::withTrashed()->findOrFail($request->car_id);

        abort_if($car->trashed(), 403, 'This listing has been removed and can no longer be reviewed.');

        $hasPurchased = Auth::user()
            ->orders()
            ->where('car_id', $car->id)
            ->where('status', 'completed')
            ->exists();

        abort_if(!$hasPurchased, 403, 'You can only review cars you have purchased.');

        $alreadyReviewed = Review::where('buyer_id', Auth::id())
            ->where('car_id', $car->id)
            ->whereNull('car_rental_id')
            ->exists();

        abort_if($alreadyReviewed, 422, 'You have already reviewed this car.');

        return view('dashboard.buyer.reviews.create', [
            'car'    => $car,
            'rental' => null,
        ]);
    }

    /**
     * Save the new review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id'        => ['required', 'exists:cars,id'],
            'car_rental_id' => ['nullable', 'exists:car_rentals,id'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'body'          => ['nullable', 'string', 'max:1000'],
        ]);

        $car    = Car::withTrashed()->findOrFail($request->car_id);
        $rental = $request->filled('car_rental_id')
            ? CarRental::findOrFail($request->car_rental_id)
            : null;

        abort_if($car->trashed(), 403, 'This listing has been removed and can no longer be reviewed.');

        if ($rental) {
            // ── Rental review guards ──────────────────────────────────
            abort_if($rental->renter_id != Auth::id(), 403);
            abort_if(!$rental->isCompleted(), 403, 'You can only review a rental after it has been completed.');
            abort_if($rental->car_id !== $car->id, 422, 'Car does not match rental booking.');

            $alreadyReviewed = Review::where('buyer_id', Auth::id())
                ->where('car_rental_id', $rental->id)
                ->exists();

            abort_if($alreadyReviewed, 422, 'You have already reviewed this rental.');
        } else {
            // ── Purchase review guards ────────────────────────────────
            $hasPurchased = Auth::user()
                ->orders()
                ->where('car_id', $car->id)
                ->where('status', 'completed')
                ->exists();

            abort_if(!$hasPurchased, 403, 'You can only review cars you have purchased.');

            $alreadyReviewed = Review::where('buyer_id', Auth::id())
                ->where('car_id', $car->id)
                ->whereNull('car_rental_id')
                ->exists();

            abort_if($alreadyReviewed, 422, 'You have already reviewed this car.');
        }

        Review::create([
            'buyer_id'      => Auth::id(),
            'car_id'        => $car->id,
            'seller_id'     => $car->seller_id,
            'car_rental_id' => $rental?->id,
            'rating'        => $request->rating,
            'body'          => $request->body,
        ]);

        Cache::forget(HomeController::CACHE_FEATURED_BIZ);

        return redirect()
            ->route('buyer.reviews.index')
            ->with('success', 'Review submitted successfully.');
    }

    /**
     * Show the form to edit an existing review.
     */
    public function edit(Review $review)
    {
        abort_if($review->buyer_id != Auth::id(), 403);

        $review->load(['car' => fn($q) => $q->withTrashed(), 'carRental']);

        return view('dashboard.buyer.reviews.edit', compact('review'));
    }

    /**
     * Save the updated review.
     */
    public function update(Request $request, Review $review)
    {
        abort_if($review->buyer_id != Auth::id(), 403);

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update([
            'rating' => $request->rating,
            'body'   => $request->body,
        ]);

        Cache::forget(HomeController::CACHE_FEATURED_BIZ);

        return redirect()
            ->route('buyer.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review)
    {
        abort_if($review->buyer_id != Auth::id(), 403);

        $review->delete();

        Cache::forget(HomeController::CACHE_FEATURED_BIZ);

        return redirect()
            ->route('buyer.reviews.index')
            ->with('success', 'Review deleted.');
    }
}