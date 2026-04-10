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
        // Review
        'status',
        'rejection_reason',
        'charged_amount',
        'reviewed_by',
        'reviewed_at',
        // Payment
        'amount_paid',
        'payment_method',
        'payment_note',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'      => 'date',
            'ends_at'        => 'date',
            'is_active'      => 'boolean',
            'priority'       => 'integer',
            'charged_amount' => 'decimal:2',
            'amount_paid'    => 'decimal:2',
            'reviewed_at'    => 'datetime',
            'paid_at'        => 'datetime',
        ];
    }

    // ── Constants ────────────────────────────────────────────────────────

    // public const PLACEMENTS = [
    //     'home'                  => 'Home Page (Horizontal Banner)',
    //     'marketplace'           => 'Marketplace (Horizontal Banner)',
    //     'news_sidebar'          => 'News Page — Right Sidebar (Vertical)',
    //     'news_detail_sidebar'   => 'News Article — Right Sidebar (Vertical)',
    //     'business_banner'       => 'Business Directory — Top Banner (Horizontal)',
    //     'car_detail_horizontal' => 'Car Detail (Horizontal Banner)',
    //     'business_profile'      => 'Business Profile — Banner (Horizontal)',
    // ];

    public const PLACEMENTS = [
        'home' => [
            'label' => 'Home Page (Horizontal Banner)',
            'image' => null,
            'video' => 'https://player.vimeo.com/video/1181908885?autoplay=1&muted=1&loop=1&controls=0&title=0&byline=0&portrait=0&badge=0`',
        ],
        'marketplace' => [
            'label' => 'Marketplace (Horizontal Banner)',
            'image' => '/images/placements/marketplace.png',
            'video' => null,
        ],
        'news_sidebar' => [
            'label' => 'News Page — Right Sidebar (Vertical)',
            'image' => '/images/placements/news_sidebar.png',
            'video' => null,
        ],
        'news_detail_sidebar' => [
            'label' => 'News Article — Right Sidebar (Vertical)',
            'image' => '/images/placements/news_detail.png',
            'video' => null,
        ],
        'business_banner' => [
            'label' => 'Business Directory — Top Banner (Horizontal)',
            'image' => '/images/placements/business_banner.png',
            'video' => null,
        ],
        'car_detail_horizontal' => [
            'label' => 'Car Detail (Horizontal Banner)',
            'image' => '/images/placements/car_detail.png',
            'video' => null,
        ],
        'business_profile' => [
            'label' => 'Business Profile — Banner (Horizontal)',
            'image' => '/images/placements/business_profile.png',
            'video' => null,
        ],
    ];

    public const PRIORITIES = [
        0 => 'Standard',
        1 => 'Featured',
        2 => 'Premium',
    ];

    public const STATUSES = [
        'pending_review' => 'Pending Review',
        'approved'       => 'Approved — Awaiting Payment',
        'rejected'       => 'Rejected',
        'published'      => 'Published',
    ];

    public const VERTICAL_PLACEMENTS = [
        'news_sidebar',
        'news_detail_sidebar',
    ];

    public const PAYMENT_METHODS = [
        'cash'  => 'Cash',
        'bank'  => 'Bank Transfer',
        'esewa' => 'eSewa',
        'other' => 'Other',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeLiveForPlacement($query, string $placement)
    {
        return $query->with('car')
            ->where('placement', $placement)
            ->where('is_active', true)
            ->where('status', 'published')
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->orderByDesc('priority')
            ->orderBy('created_at');
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    // ── Computed helpers ──────────────────────────────────────────────────

    public function isLive(): bool
    {
        if (!$this->is_active || $this->status !== 'published') {
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

    /**
     * Calculate the expected charge from the live pricing rule.
     * Returns null if no rule exists for this placement+priority.
     */
    public function calculateExpectedCharge(): ?float
    {
        if (!$this->starts_at || !$this->ends_at) {
            return null;
        }

        $rule = AdPricingRule::for($this->placement, $this->priority);

        return $rule?->calculateCharge($this->starts_at, $this->ends_at);
    }

    /** Duration in days (inclusive of both ends) */
    public function durationDays(): ?int
    {
        if (!$this->starts_at || !$this->ends_at) {
            return null;
        }

        return (int) $this->starts_at->startOfDay()->diffInDays($this->ends_at->startOfDay()) + 1;
    }

    public function placementLabel(): string
    {
        return self::PLACEMENTS[$this->placement] ?? ucfirst($this->placement);
    }

    public function priorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? 'Standard';
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            2       => 'bg-amber-100 text-amber-700',
            1       => 'bg-purple-100 text-purple-700',
            default => 'bg-slate-100 text-slate-500',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'published'      => 'bg-green-100 text-green-700',
            'approved'       => 'bg-blue-100 text-blue-700',
            'rejected'       => 'bg-red-100 text-red-700',
            default          => 'bg-yellow-100 text-yellow-700', // pending_review
        };
    }

    public function isVertical(): bool
    {
        return in_array($this->placement, self::VERTICAL_PLACEMENTS);
    }

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