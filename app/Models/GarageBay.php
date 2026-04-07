<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageBay extends Model
{
    protected $fillable = [
        'user_id',
        'bay_number',
        'status',
        'walkin_customer_name',
        'service_note',
        'estimated_finish_at',
        'appointment_id',
    ];

    protected $casts = [
        'estimated_finish_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function garage(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(GarageAppointment::class, 'appointment_id');
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

    public function isWalkin(): bool
    {
        return $this->isOccupied() && $this->appointment_id === null;
    }

    public function statusColour(): string
    {
        return match ($this->status) {
            'available' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            'occupied'  => 'bg-red-50 text-red-600 border-red-200',
            default     => 'bg-slate-50 text-slate-500 border-slate-200',
        };
    }

    public function finishLabel(): string
    {
        if (!$this->estimated_finish_at) return 'No time set';

        $diff = now()->diffInMinutes($this->estimated_finish_at, false);

        if ($diff <= 0) return 'Overdue';
        if ($diff < 60) return "~{$diff} min";

        return '~' . round($diff / 60, 1) . ' hr';
    }

    /** Display name for who is in the bay */
    public function occupantLabel(): string
    {
        if ($this->appointment) {
            return $this->appointment->customer->name ?? 'Appointment customer';
        }

        return $this->walkin_customer_name ?: 'Walk-in customer';
    }
}