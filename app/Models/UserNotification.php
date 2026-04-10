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

    // ── Colour + icon helpers (used in the notifications blade view) ───

    /**
     * Returns Tailwind bg + text class pair based on notification type.
     *
     * Green  → anything approved, confirmed, completed, published, arrived
     * Red    → anything rejected or cancelled
     * Blue   → informational (deposit confirmed, slot active)
     * Slate  → fallback
     */
    public function colourClasses(): array
    {
        return match (true) {
            in_array($this->type, [
                'ad_approved',
                'ad_published',
                'slot_approved',
                'appointment_approved',
                'appointment_completed',
                'order_confirmed',
                'order_completed',
                'preorder_converted',
                'account_approved',
            ]) => ['bg-emerald-100', 'text-emerald-600'],

            in_array($this->type, [
                'ad_rejected',
                'slot_rejected',
                'appointment_rejected',
                'order_cancelled',
                'preorder_cancelled',
                'account_rejected',
            ]) => ['bg-red-100', 'text-red-600'],

            in_array($this->type, [
                'slot_occupied',
                'preorder_deposit_confirmed',
            ]) => ['bg-blue-100', 'text-blue-600'],

            default => ['bg-slate-100', 'text-slate-500'],
        };
    }

    /**
     * Returns 'check', 'cross', 'bolt', or 'info' — mapped to SVG icons in the blade view.
     */
    public function iconType(): string
    {
        return match (true) {
            in_array($this->type, [
                'ad_approved',
                'ad_published',
                'slot_approved',
                'appointment_approved',
                'appointment_completed',
                'order_confirmed',
                'order_completed',
                'preorder_converted',
                'account_approved',
            ]) => 'check',

            in_array($this->type, [
                'ad_rejected',
                'slot_rejected',
                'appointment_rejected',
                'order_cancelled',
                'preorder_cancelled',
                'account_rejected',
            ]) => 'cross',

            in_array($this->type, [
                'slot_occupied',
                'preorder_deposit_confirmed',
            ]) => 'bolt',

            default => 'info',
        };
    }
}