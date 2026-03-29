<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsArticle extends Model
{
    use HasFactory;

    // Specify the table name explicitly
    protected $table = 'news_article';

    // Allow mass assignment
    protected $fillable = [
        'title',
        'subtitle',
        'author_name',
        'author_role',
        'author_image',
        'read_time',
        'badge_text',
        'published_at',
        'hero_image',
        'hero_caption',
        'content_html',

        'vehicle_specs',
        'content_images',
        'related_articles',

        // NEW FIELDS
        'quote',
        'title2',
        'content_html2',
        'content_images2',
        'title3',
        'content_html3',
    ];

    protected $casts = [
        'vehicle_specs' => 'array',
        'content_images' => 'array',
        'related_articles' => 'array',
        'content_images2' => 'array',
        'published_at' => 'date',
    ];
}
