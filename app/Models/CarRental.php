<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Review;

class CarRental extends Model
{
    protected $fillable = [
        'car_id',
        'car_snapshot_name',
        'renter_id',
        'owner_id',
        'pickup_date',
        'return_date',
        'actual_return_date',
        'total_days',
        'price_per_day',
        'deposit_amount',
        'total_price',
        'status',
        'renter_name',
        'renter_phone',
        'renter_email',
        'notes',
        'cancellation_reason',
        'cancelled_by',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date'        => 'date',
            'return_date'        => 'date',
            'actual_return_date' => 'date',
            'total_days'         => 'integer',
            'price_per_day'      => 'integer',
            'deposit_amount'     => 'integer',
            'total_price'        => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    /**
     * The car being rented.
     * car_id is nullable (nullOnDelete) — always use car_snapshot_name
     * as the safe display fallback when car() returns null.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class)->withTrashed();
    }

    /** The buyer who placed the rental booking. */
    public function renter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    /** The seller / business who owns the listing. */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /** Reviews written for this specific rental booking. */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'car_rental_id');
    }

    // ── Display helpers ───────────────────────────────────────────────

    /**
     * Safe car name for display at all times.
     * Falls back to the snapshot when the car record has been deleted.
     */
    public function carDisplayName(): string
    {
        return $this->car?->displayName() ?? $this->car_snapshot_name ?? 'Deleted Listing';
    }

    /** e.g. "NRs 2,500 / day" */
    public function formattedDailyRate(): string
    {
        return 'NRs ' . number_format($this->price_per_day) . ' / day';
    }

    /** e.g. "NRs 25,000" */
    public function formattedTotalPrice(): string
    {
        return 'NRs ' . number_format($this->total_price);
    }

    /** e.g. "NRs 10,000" or null when no deposit was required. */
    public function formattedDeposit(): ?string
    {
        return $this->deposit_amount
            ? 'NRs ' . number_format($this->deposit_amount)
            : null;
    }

    // ── Lifecycle helpers ─────────────────────────────────────────────

    /**
     * Cancellation is only allowed while the booking is pending or confirmed.
     * Once the car has been picked up (active) it cannot be cancelled through
     * the platform — the owner must complete or handle it offline.
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // ── Status badge helpers (for views) ─────────────────────────────

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pending',
            'confirmed' => 'Confirmed',
            'active'    => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default     => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'active'    => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }
}