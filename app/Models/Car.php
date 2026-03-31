<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    protected $fillable = [
        'seller_id',
        'brand',
        'model',
        'year',
        'variant',
        'drivetrain',
        'mileage',
        'range_km',
        'battery_kwh',
        'color',
        'condition',
        'price',
        'price_negotiable',
        'location',
        'description',
        'primary_image',
        'status',
        'stock_quantity',
        'is_preorder',
        'expected_arrival_date',
        'preorder_deposit',
    ];

    protected function casts(): array
    {
        return [
            'price_negotiable'       => 'boolean',
            'price'                  => 'integer',
            'mileage'                => 'integer',
            'range_km'               => 'integer',
            'battery_kwh'            => 'float',
            'stock_quantity'         => 'integer',
            'is_preorder'            => 'boolean',
            'expected_arrival_date'  => 'date',
            'preorder_deposit'       => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    /** The seller (or business) who listed this car */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /** All orders placed for this car */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** All reviews left for this car */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /** e.g. "NRs 5,500,000" */
    public function formattedPrice(): string
    {
        return 'NRs ' . number_format($this->price);
    }

    /** e.g. "2024 Tesla Model 3 Long Range" */
    public function displayName(): string
    {
        return "{$this->year} {$this->brand} {$this->model}"
            . ($this->variant ? " {$this->variant}" : '');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming' || $this->is_preorder;
    }

    public function isPreorderable(): bool
    {
        return $this->is_preorder && $this->status === 'upcoming' && $this->preorder_deposit > 0;
    }

    /** All images for this car */
    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    /** The primary/cover image */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(CarImage::class)->where('is_primary', true);
    }

    /** Check if the car has stock available */
    public function inStock(): bool
    {
        return $this->stock_quantity > 0 && $this->status === 'available';
    }

    /** Reduce stock by 1 after a purchase. Auto-marks sold when stock hits 0 */
    public function decrementStock(): void
    {
        $this->decrement('stock_quantity');

        if ($this->stock_quantity <= 0) {
            $this->update(['status' => 'sold']);
        }
}
}