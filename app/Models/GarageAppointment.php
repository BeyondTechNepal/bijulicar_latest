<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageAppointment extends Model
{
    protected $fillable = [
        'garage_user_id',
        'customer_user_id',
        'bay_number',
        'status',
        'service_description',
        'requested_at',
        'estimated_finish_at',
        'rejection_reason',
        'garage_note',
    ];

    protected $casts = [
        'requested_at'        => 'datetime',
        'estimated_finish_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    /** The garage owner */
    public function garage(): BelongsTo
    {
        return $this->belongsTo(User::class, 'garage_user_id');
    }

    /** The customer who booked */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function isRejected(): bool   { return $this->status === 'rejected'; }
    public function isCompleted(): bool  { return $this->status === 'completed'; }

    /** Badge colour class for the dashboard status pill */
    public function statusColour(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-amber-100 text-amber-700 border-amber-200',
            'approved'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'rejected'  => 'bg-red-100 text-red-700 border-red-200',
            'completed' => 'bg-slate-100 text-slate-600 border-slate-200',
            default     => 'bg-slate-100 text-slate-500 border-slate-200',
        };
    }
}