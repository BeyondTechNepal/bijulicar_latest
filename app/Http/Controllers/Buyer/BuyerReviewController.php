<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerReviewController extends Controller
{
    /**
     * Show all reviews written by the logged-in buyer.
     */
    public function index()
    {
        $reviews = Auth::user()
            ->reviews()
            ->with('car')       // load car details in the same query
            ->latest()
            ->paginate(10);

        return view('dashboard.buyer.reviews.index', compact('reviews'));
    }

    /**
     * Show the form to write a new review for a purchased car.
     */
    public function create(Car $car)
    {
        // Buyer must have actually purchased this car
        $hasPurchased = Auth::user()
            ->orders()
            ->where('car_id', $car->id)
            ->where('status', 'completed')
            ->exists();

        abort_if(!$hasPurchased, 403, 'You can only review cars you have purchased.');

        // Cannot review the same car twice
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

        $car = Car::findOrFail($request->car_id);

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

        return redirect()
            ->route('buyer.reviews.index')
            ->with('success', 'Review submitted successfully.');
    }

    /**
     * Show the form to edit an existing review.
     */
    public function edit(Review $review)
    {
        // Only the buyer who wrote this review can edit it
        abort_if($review->buyer_id !== Auth::id(), 403);

        $review->load('car');

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

        return redirect()
            ->route('buyer.reviews.index')
            ->with('success', 'Review deleted.');
    }
}