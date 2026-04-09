<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }

    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // ── Icon + colour map (used in blade views) ────────────────────────

    /**
     * Returns a Tailwind colour class pair based on notification type.
     * Format: [bg class, text class]
     */
    public function colourClasses(): array
    {
        return match (true) {
            str_ends_with($this->type, '_approved') || str_ends_with($this->type, '_published')
                => ['bg-emerald-100', 'text-emerald-600'],
            str_ends_with($this->type, '_rejected')
                => ['bg-red-100', 'text-red-600'],
            default
                => ['bg-slate-100', 'text-slate-500'],
        };
    }

    /**
     * Returns a simple SVG path string for the notification icon dot.
     */
    public function iconType(): string
    {
        return match (true) {
            str_ends_with($this->type, '_approved') || str_ends_with($this->type, '_published') => 'check',
            str_ends_with($this->type, '_rejected') => 'cross',
            default => 'info',
        };
    }
}