<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarExperience extends Model
{
    protected $fillable = [
        'user_id',
        'car_id',
        'external_car_name',
        'title',
        'trip_context',
        'body',
        'experience_type',
        'status',
        'admin_note',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    /** The user who wrote this experience. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The BijuliCar listing this experience is linked to.
     * car_id is nullable (nullOnDelete) — use carDisplayName() for safe display.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class)->withTrashed();
    }

    // ── Display helpers ───────────────────────────────────────────────

    /**
     * Safe car name for display at all times.
     * If linked to a BijuliCar listing, uses the listing's display name.
     * Falls back to external_car_name when car_id is null or the listing
     * has been deleted.
     */
    public function carDisplayName(): string
    {
        return $this->car?->displayName() ?? $this->external_car_name ?? 'Unknown Car';
    }

    /**
     * Whether this experience is linked to a BijuliCar listing.
     */
    public function isLinkedToBijuliCar(): bool
    {
        return !is_null($this->car_id);
    }

    // ── Experience type helpers ───────────────────────────────────────

    public function experienceTypeLabel(): string
    {
        return match ($this->experience_type) {
            'rental'   => 'Rental',
            'purchase' => 'Purchase',
            'general'  => 'General',
            default    => ucfirst($this->experience_type),
        };
    }

    public function experienceTypeBadgeClasses(): string
    {
        return match ($this->experience_type) {
            'rental'   => 'bg-blue-100 text-blue-700',
            'purchase' => 'bg-green-100 text-green-700',
            'general'  => 'bg-slate-100 text-slate-600',
            default    => 'bg-slate-100 text-slate-600',
        };
    }

    // ── Status helpers ────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default    => ucfirst($this->status),
        };
    }

    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'pending'  => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            default    => 'bg-slate-100 text-slate-600',
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────

    /** Only approved experiences — used on all public-facing queries. */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /** Filter by a BijuliCar car_id — used on the car detail page. */
    public function scopeForCar($query, int $carId)
    {
        return $query->where('car_id', $carId);
    }
}