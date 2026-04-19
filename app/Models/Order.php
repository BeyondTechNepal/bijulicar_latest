<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',           // snapshot — set at order creation, survives car deletion
        'car_id',
        'car_snapshot_name',   // snapshot — preserved even after car is deleted
        'status',
        'total_price',
        'notes',
        'buyer_name',
        'buyer_phone',
        'buyer_email',
        'ordered_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
            'ordered_at'  => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    /** The buyer who placed this order */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * The seller who owns the listing.
     * Uses the seller_id snapshot, so this works even after car deletion.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The car this order is for.
     * car_id is nullable (set to NULL when the car is deleted), so always
     * guard against null before accessing car properties. Use
     * $order->car_snapshot_name as the safe display fallback.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class)->withTrashed();
    }

    /** The purchase record once payment is made */
    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }

    /** The pre-order this order was converted from (if any) */
    public function preOrder(): HasOne
    {
        return $this->hasOne(PreOrder::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /** Can this order still be cancelled? */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Returns true when this order was automatically closed because the
     * seller confirmed a different buyer's order for the same single-stock
     * listing.
     */
    public function isSoldOut(): bool
    {
        return $this->status === 'sold_out';
    }

    /**
     * Human-readable car name that is safe to display at all times.
     * Falls back to the snapshot stored at order creation if the live
     * car record has been deleted.
     */
    public function carDisplayName(): string
    {
        return $this->car?->displayName() ?? $this->car_snapshot_name ?? 'Deleted Listing';
    }

    /** Returns a color string for status badges in the view */
    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            'sold_out'  => 'gray',
            default     => 'gray',
        };
    }

    /** Human-readable label for the status badge */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'sold_out'  => 'Sold Out',
            default     => ucfirst($this->status),
        };
    }
}