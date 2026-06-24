<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExperienceComment extends Model
{
    protected $fillable = [
        'car_experience_id',
        'user_id',
        'parent_id',
        'body',
        'is_edited',
    ];

    protected function casts(): array
    {
        return [
            'is_edited' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    /** The experience this comment belongs to. */
    public function experience(): BelongsTo
    {
        return $this->belongsTo(CarExperience::class, 'car_experience_id');
    }

    /** The user who wrote this comment. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The parent comment — null if this is a top-level comment.
     * Used to check depth before allowing a reply.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ExperienceComment::class, 'parent_id');
    }

    /**
     * Direct replies to this comment (one level only).
     * We never load replies->replies to enforce the 2-level limit.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ExperienceComment::class, 'parent_id')
                    ->with('user:id,name,profile_photo')
                    ->oldest();
    }

    // ── Scopes ────────────────────────────────────────────────────────

    /** Top-level comments only (no replies). */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /** Whether this comment is a reply to another comment. */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /** Whether the given user owns this comment. */
    public function ownedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}