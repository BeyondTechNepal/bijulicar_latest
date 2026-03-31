<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Optional if your table is named 'news', but good for clarity)
     */
    protected $table = 'news';

    /**
     * Columns aligned strictly with your DB structure.
     * Note: 'tech_notetext' is fixed here.
     */
    protected $fillable = [
        'admin_id',
        'title',
        'title_highlight',
        'title_suffix',
        'slug',
        'author_initials',
        'author_name',
        'author_role',
        'hero_image',
        'figure_caption',
        'lead_paragraph',
        'section_1_title',
        'section_1_content',
        'tech_specs',
        'tech_note', // Fixed to match Column 15
        'section_2_title',
        'section_2_content',
        'quote_text',
        'quote_author',
        'quote_author_title',
        'section_3_title',
        'section_3_content',
        'is_published',
    ];

    /**
     * Get the admin that authored the news.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Casts for JSON and Boolean data types.
     */
    protected $casts = [
        'tech_specs' => 'array',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Auto-generate slug if not provided.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    /**
     * Use slug for Route Model Binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
