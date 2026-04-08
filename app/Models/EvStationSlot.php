<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvStationSlot extends Model
{
    protected $fillable = [
        'user_id',
        'slot_number',
        'status',
        'free_at',
        'occupied_by',
    ];

    protected $casts = [
        'free_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    /** The EV station owner */
    public function station(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** The customer who requested or is using this slot */
    public function occupant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'occupied_by');
    }

    // ── Status helpers ─────────────────────────────────────────────────

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Customer has requested this slot — waiting for station approval.
     * Still shows as available on the map (green) until approved.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isBooked(): bool
{
    return $this->status === 'booked';
}

    /**
     * Station approved — slot is confirmed in use. Shows red on the map.
     */
    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    public function isRequestable(): bool
    {
        return $this->status === 'available';
    }

    /** Human-readable time remaining label for the dashboard */
    public function freeAtLabel(): string
    {
        if (!$this->free_at) {
            return 'Unknown';
        }

        $diff = now()->diffInMinutes($this->free_at, false);

        if ($diff <= 0) return 'Overdue';
        if ($diff < 60) return "~{$diff} min";

        return '~' . round($diff / 60, 1) . ' hr';
    }

    /** Badge colour for the station dashboard */
    public function statusColour(): string
    {
        return match ($this->status) {
            'available' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            'pending'   => 'bg-amber-50 text-amber-600 border-amber-200',
            'booked'    => 'bg-blue-50 text-blue-600 border-blue-200',
            'occupied'  => 'bg-red-50 text-red-600 border-red-200',
            default     => 'bg-slate-50 text-slate-500 border-slate-200',
        };
    }
}