<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Negotiation extends Model
{
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'car_id',
        'offered_price',
        'listed_price',
        'status',
        'rounds',
        'message',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'offered_price' => 'integer',
            'listed_price'  => 'integer',
            'rounds'        => 'integer',
            'expires_at'    => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // ── Status helpers ─────────────────────────────────────────────────

    public function isPendingSeller(): bool { return $this->status === 'pending_seller'; }
    public function isPendingBuyer(): bool  { return $this->status === 'pending_buyer'; }
    public function isAccepted(): bool      { return $this->status === 'accepted'; }
    public function isDeclined(): bool      { return $this->status === 'declined'; }
    public function isExpired(): bool       { return $this->status === 'expired'; }
    public function isCancelled(): bool     { return $this->status === 'cancelled'; }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending_seller', 'pending_buyer']);
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['accepted', 'declined', 'expired', 'cancelled']);
    }

    /** Max 3 rounds of counter-offers allowed */
    public function canCounter(): bool
    {
        return $this->rounds < 3;
    }

    public function discountPercent(): float
    {
        if ($this->listed_price <= 0) return 0;
        return round((($this->listed_price - $this->offered_price) / $this->listed_price) * 100, 1);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending_seller' => 'yellow',
            'pending_buyer'  => 'blue',
            'accepted'       => 'green',
            'declined'       => 'red',
            'expired'        => 'gray',
            'cancelled'      => 'gray',
            default          => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_seller' => 'Awaiting Seller',
            'pending_buyer'  => 'Seller Countered',
            'accepted'       => 'Accepted',
            'declined'       => 'Declined',
            'expired'        => 'Expired',
            'cancelled'      => 'Cancelled',
            default          => ucfirst($this->status),
        };
    }
}