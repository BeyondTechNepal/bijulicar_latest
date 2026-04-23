<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'buyer_id',
        'car_id',
        'seller_id',
        'car_rental_id',
        'rating',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    // Relationships

    /** The buyer who wrote this review */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /** The car being reviewed */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /** The seller who listed the car */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /** The rental booking that prompted this review (null = purchase review) */
    public function carRental(): BelongsTo
    {
        return $this->belongsTo(CarRental::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /** Whether this review was left after a rental (vs. a purchase) */
    public function isRentalReview(): bool
    {
        return !is_null($this->car_rental_id);
    }

    /** e.g. "★★★★☆" */
    public function starDisplay(): string
    {
        return str_repeat('★', $this->rating)
             . str_repeat('☆', 5 - $this->rating);
    }

    /** Human-readable source label for display in views */
    public function sourceLabel(): string
    {
        return $this->isRentalReview() ? 'Rental' : 'Purchase';
    }

    /** Tailwind colour classes for the source badge */
    public function sourceBadgeClasses(): string
    {
        return $this->isRentalReview()
            ? 'bg-blue-100 text-blue-700'
            : 'bg-[#4ade80]/15 text-[#16a34a]';
    }
}