<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPricingRule extends Model
{
    protected $fillable = [
        'placement',
        'priority',
        'price_per_day',
        'min_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
            'is_active'     => 'boolean',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────────────────

    /** Only rules the admin has not disabled */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Helpers ─────────────────────────────────────────────────────────

    /**
     * Look up the rule for a given placement + priority.
     * Returns null if admin hasn't set one or has deactivated it.
     */
    public static function for(string $placement, int $priority): ?self
    {
        return static::where('placement', $placement)
            ->where('priority', $priority)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Calculate the total charge for an ad given its date range.
     *
     * @param  \Carbon\Carbon|\Illuminate\Support\Carbon  $startsAt
     * @param  \Carbon\Carbon|\Illuminate\Support\Carbon  $endsAt
     */
    public function calculateCharge($startsAt, $endsAt): float
    {
        $days = (int) $startsAt->startOfDay()->diffInDays($endsAt->startOfDay()) + 1;
        return round($this->price_per_day * max($days, $this->min_days), 2);
    }

    /** Human label for the priority tier */
    public function priorityLabel(): string
    {
        return Advertisement::PRIORITIES[$this->priority] ?? 'Standard';
    }

    /** Human label for the placement */
    public function placementLabel(): string
    {
        return Advertisement::PLACEMENTS[$this->placement] ?? ucfirst($this->placement);
    }
}