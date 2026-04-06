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

    /** The customer currently occupying this slot (if any) */
    public function occupant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'occupied_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    /** Human-readable time remaining label for the dashboard */
    public function freeAtLabel(): string
    {
        if (!$this->free_at) {
            return 'Unknown';
        }

        $diff = now()->diffInMinutes($this->free_at, false);

        if ($diff <= 0) {
            return 'Overdue';
        }

        if ($diff < 60) {
            return "~{$diff} min";
        }

        return '~' . round($diff / 60, 1) . ' hr';
    }
}