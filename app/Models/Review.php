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

    //  Helpers 

    /** e.g. "★★★★☆" */
    public function starDisplay(): string
    {
        return str_repeat('★', $this->rating)
             . str_repeat('☆', 5 - $this->rating);
    }
}