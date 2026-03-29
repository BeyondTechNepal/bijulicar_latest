<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'car_id',
        'link_url',
        'image',
        'placement',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at'   => 'date',
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────

    /** The business user who owns this ad */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** The car listing this ad promotes (optional) */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────

    /** Is this ad currently running? */
    public function isLive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->toDateString();

        if ($this->starts_at && $this->starts_at->toDateString() > $today) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->toDateString() < $today) {
            return false;
        }

        return true;
    }

    /** Human-readable placement label */
    public function placementLabel(): string
    {
        return match ($this->placement) {
            'home'        => 'Home Page',
            'marketplace' => 'Marketplace',
            default       => ucfirst($this->placement),
        };
    }
}