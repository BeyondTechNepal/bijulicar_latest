<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

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
        // ── Rental fields ─────────────────────────────────────────────
        'listing_type',
        'rent_price_per_day',
        'rent_min_days',
        'rent_max_days',
        'rent_deposit',
    ];

    protected function casts(): array
    {
        return [
            'price_negotiable'      => 'boolean',
            'price'                 => 'integer',
            'mileage'               => 'integer',
            'range_km'              => 'integer',
            'battery_kwh'           => 'float',
            'stock_quantity'        => 'integer',
            'is_preorder'           => 'boolean',
            'expected_arrival_date' => 'date',
            'preorder_deposit'      => 'integer',
            // ── Rental casts ──────────────────────────────────────────
            'rent_price_per_day'    => 'integer',
            'rent_min_days'         => 'integer',
            'rent_max_days'         => 'integer',
            'rent_deposit'          => 'integer',
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

    /** All rental bookings for this car. */
    public function rentals(): HasMany
    {
        return $this->hasMany(CarRental::class);
    }

    /** All reviews left for this car */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /** All experiences shared about this car */
    public function experiences(): HasMany
    {
        return $this->hasMany(CarExperience::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /** e.g. "NRs 5,500,000" */
    public function formattedPrice(): string
    {
        if ($this->price) {
            return 'NRs ' . number_format($this->price);
        }
        if ($this->rent_price_per_day) {
            return 'NRs ' . number_format($this->rent_price_per_day) . '/day';
        }
        return '—';
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

    // ── Listing-type helpers ──────────────────────────────────────────

    /** True when the car can be purchased (listing_type is 'sale' or 'both'). */
    public function isSaleable(): bool
    {
        // Default to 'sale' when listing_type is null (pre-migration rows)
        return in_array($this->listing_type ?? 'sale', ['sale', 'both']);
    }

    /** True when the car can be rented (listing_type is 'rent' or 'both'). */
    public function isRentable(): bool
    {
        return in_array($this->listing_type ?? 'sale', ['rent', 'both']);
    }

    // ── Rental helpers ────────────────────────────────────────────────

    /** e.g. "NRs 2,500 / day" */
    public function formattedRentPrice(): string
    {
        return 'NRs ' . number_format($this->rent_price_per_day) . ' / day';
    }

    /**
     * Human-readable rental duration constraint.
     * e.g. "1–7 days", "Min 3 days", "Max 14 days", "No limit"
     */
    public function rentDurationLabel(): string
    {
        $min = $this->rent_min_days ?? 1;
        $max = $this->rent_max_days;

        if ($max) {
            return "{$min}–{$max} days";
        }

        return $min > 1 ? "Min {$min} days" : 'No limit';
    }

    /**
     * Eager-load scope: adds `active_rentals_count` to any Car query so that
     * hasActiveRental() and availableStockForRent() can read it without firing
     * an extra COUNT query per car.
     *
     * Usage:
     *   Car::withActiveRentalCount()->paginate(9);
     *   Car::with([...])->withActiveRentalCount()->findOrFail($id);
     */
    public function scopeWithActiveRentalCount($query)
    {
        return $query->withCount([
            'rentals as active_rentals_count' => fn ($q) =>
                $q->whereIn('status', ['confirmed', 'active']),
        ]);
    }

    /**
     * Resolve the number of confirmed/active rentals for this car.
     *
     * If the model was loaded via scopeWithActiveRentalCount() the count is
     * already present as an attribute — no extra query needed.
     * Otherwise fall back to a live COUNT so the method is always safe to call
     * even on a model fetched without the scope.
     */
    private function resolveActiveRentalCount(): int
    {
        // withCount() stores the result as a snake_case attribute on the model.
        if (array_key_exists('active_rentals_count', $this->getAttributes())) {
            return (int) $this->active_rentals_count;
        }

        // Fallback: single COUNT query (single-car contexts such as order placement).
        return $this->rentals()
            ->whereIn('status', ['confirmed', 'active'])
            ->count();
    }

    /**
     * How many units are free to be rented right now.
     * stock_quantity minus the number of confirmed/active rentals.
     *
     * Uses the eager-loaded `active_rentals_count` when available so that
     * iterating over a paginated collection does not fire a query per car.
     */
    public function availableStockForRent(): int
    {
        return max(0, $this->stock_quantity - $this->resolveActiveRentalCount());
    }

    /**
     * Returns true only when ALL stock units are out on confirmed/active rental —
     * i.e. there is no physical unit left to hand over.
     *
     * A 3-stock car with 1 unit on rental still has 2 units free → returns false.
     * A 1-stock car with 1 unit on rental has nothing left → returns true.
     *
     * Delegates to availableStockForRent() so both methods share a single
     * COUNT query (or a single eager-loaded attribute) instead of each firing
     * their own query.
     */
    public function hasActiveRental(): bool
    {
        return $this->availableStockForRent() === 0;
    }

    /**
     * Returns true when the requested date range overlaps with any existing
     * confirmed or active rental for this car.
     *
     * Overlap: existing.pickup_date <= requested.return_date
     *      AND existing.return_date >= requested.pickup_date
     *
     * @param  string|\DateTimeInterface  $pickupDate
     * @param  string|\DateTimeInterface  $returnDate
     * @param  int|null  $excludeRentalId  Exclude a rental ID (useful when editing).
     */
    public function hasOverlappingRental(
        string|\DateTimeInterface $pickupDate,
        string|\DateTimeInterface $returnDate,
        ?int $excludeRentalId = null
    ): bool {
        return $this->rentals()
            ->whereIn('status', ['confirmed', 'active'])
            ->where('pickup_date', '<=', $returnDate)
            ->where('return_date', '>=', $pickupDate)
            ->when($excludeRentalId, fn ($q) => $q->where('id', '!=', $excludeRentalId))
            ->exists();
    }
}