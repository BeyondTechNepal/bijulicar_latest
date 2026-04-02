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
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at'   => 'date',
            'is_active' => 'boolean',
            'priority'  => 'integer',
        ];
    }

    // ── Constants ──────────────────────────────────────────────────────

    /** All valid placement keys mapped to human labels */
    public const PLACEMENTS = [
        'home'                  => 'Home Page (Horizontal Banner)',
        'marketplace'           => 'Marketplace (Horizontal Banner)',
        'news_sidebar'          => 'News Page — Right Sidebar (Vertical)',
        'news_detail_sidebar'   => 'News Article — Right Sidebar (Vertical)',
        'business_banner'       => 'Business Directory — Top Banner (Horizontal)',
        'car_detail_sidebar'    => 'Car Detail — Sidebar (Vertical)',
        'business_profile'      => 'Business Profile — Banner (Horizontal)',
    ];

    /** Priority tiers for the bidding / boost system */
    public const PRIORITIES = [
        0 => 'Standard',
        1 => 'Featured',
        2 => 'Premium',
    ];

    /** Placements rendered as a vertical sidebar card */
    public const VERTICAL_PLACEMENTS = [
        'news_sidebar',
        'news_detail_sidebar',
        'car_detail_sidebar',
    ];

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

    // ── Scopes ─────────────────────────────────────────────────────────

    /**
     * Scope: only live ads for a given placement, ordered by priority DESC
     * then by creation date (earlier = longer-running = shown first on ties).
     */
    public function scopeLiveForPlacement($query, string $placement)
    {
        return $query->with('car')
            ->where('placement', $placement)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->orderByDesc('priority')
            ->orderBy('created_at');
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
        return self::PLACEMENTS[$this->placement] ?? ucfirst($this->placement);
    }

    /** Human-readable priority label */
    public function priorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? 'Standard';
    }

    /** Badge colour class for priority (Tailwind) */
    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            2       => 'bg-amber-100 text-amber-700',   // Premium — gold
            1       => 'bg-purple-100 text-purple-700', // Featured — purple
            default => 'bg-slate-100 text-slate-500',   // Standard — grey
        };
    }

    /** Is this a vertical sidebar-style placement? */
    public function isVertical(): bool
    {
        return in_array($this->placement, self::VERTICAL_PLACEMENTS);
    }

    /** Resolved click target URL */
    public function targetUrl(): ?string
    {
        if ($this->link_url) {
            return $this->link_url;
        }

        if ($this->car_id) {
            return route('cars.show', $this->car_id);
        }

        return null;
    }
}