<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * RELATIONSHIP: One Category HAS MANY News articles.
     * * This allows you to call $category->news to get a collection
     * of all articles assigned to this category.
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }

    /**
     * BOOT METHOD: Auto-generate Slugs
     * * A "Production-Ready" trick: Automatically turn "Tech News" into "tech-news"
     * whenever a category is created, so you don't have to do it in the Controller.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * HELPER: Get Route Key Name
     * * This allows you to use /categories/technology instead of /categories/1
     * in your URLs automatically (Route Model Binding).
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
