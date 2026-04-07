<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessNews extends Model
{
    use HasFactory;

    protected $table = 'business_news';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'hero_image',
        'figure_caption',
        'lead_paragraph',
        'section_1_title',
        'section_1_content',
        'quote_text',
        'quote_author',
        'section_2_title',
        'section_2_content',
        'section_3_title',
        'section_3_content',
        'section_4_title',
        'section_4_content',
        'author_name',
        'author_role',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    /**
     * The business user who wrote this article.
     */
    public function business()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The category this article belongs to (shared with admin news).
     */
    public function newscategory()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    // ── Auto-slug ─────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                // Make slug unique by appending user_id to avoid collisions with admin slugs
                $base = Str::slug($news->title);
                $slug = $base;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $count++;
                }
                $news->slug = $slug;
            }
        });
    }

    // ── Route model binding via slug ──────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Helper: readable business name ───────────────────────────────────

    public function getBusinessNameAttribute(): string
    {
        return $this->business?->businessVerification?->business_name
            ?? $this->business?->name
            ?? 'Business';
    }
}