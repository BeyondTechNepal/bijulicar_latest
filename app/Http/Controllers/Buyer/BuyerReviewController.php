<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\HomeController;

class BuyerReviewController extends Controller
{
    /**
     * Show all reviews written by the logged-in buyer.
     */
    public function index()
    {
        $reviews = Auth::user()
            ->reviews()
            ->with(['car' => fn($q) => $q->withTrashed()])
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.reviews.index', compact('reviews'));
    }

    /**
     * Show the form to write a new review for a purchased car.
     */
    public function create(Car $car)
    {
        // Re-fetch withTrashed so a soft-deleted car doesn't 404
        $car = Car::withTrashed()->findOrFail($car->id);

        // Cannot write a new review for a listing that has been removed
        abort_if($car->trashed(), 403, 'This listing has been removed and can no longer be reviewed.');

        $hasPurchased = Auth::user()
            ->orders()
            ->where('car_id', $car->id)
            ->where('status', 'completed')
            ->exists();

        abort_if(!$hasPurchased, 403, 'You can only review cars you have purchased.');

        $alreadyReviewed = Review::where('buyer_id', Auth::id())
            ->where('car_id', $car->id)
            ->exists();

        abort_if($alreadyReviewed, 422, 'You have already reviewed this car.');

        return view('dashboard.buyer.reviews.create', compact('car'));
    }

    /**
     * Save the new review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body'   => ['nullable', 'string', 'max:1000'],
        ]);

        $car = Car::withTrashed()->findOrFail($request->car_id);

        abort_if($car->trashed(), 403, 'This listing has been removed and can no longer be reviewed.');

        // Same checks as create()
        $hasPurchased = Auth::user()
            ->orders()
            ->where('car_id', $car->id)
            ->where('status', 'completed')
            ->exists();

        abort_if(!$hasPurchased, 403, 'You can only review cars you have purchased.');

        $alreadyReviewed = Review::where('buyer_id', Auth::id())
            ->where('car_id', $car->id)
            ->exists();

        abort_if($alreadyReviewed, 422, 'You have already reviewed this car.');

        Review::create([
            'buyer_id'  => Auth::id(),
            'car_id'    => $car->id,
            'seller_id' => $car->seller_id,
            'rating'    => $request->rating,
            'body'      => $request->body,
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
        abort_if($review->buyer_id !== Auth::id(), 403);

        $review->load(['car' => fn($q) => $q->withTrashed()]);

        return view('dashboard.buyer.reviews.edit', compact('review'));
    }

    /**
     * Save the updated review.
     */
    public function update(Request $request, Review $review)
    {
        abort_if($review->buyer_id !== Auth::id(), 403);

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
        abort_if($review->buyer_id !== Auth::id(), 403);

        $review->delete();

        Cache::forget(HomeController::CACHE_FEATURED_BIZ);

        return redirect()
            ->route('buyer.reviews.index')
            ->with('success', 'Review deleted.');
    }
}