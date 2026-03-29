<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CarImage extends Model
{
    protected $fillable = [
        'car_id',
        'path',
        'alt',
        'sort_order',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary'  => 'boolean',
            'sort_order'  => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    /** The car this image belongs to */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    /** Full public URL for use in <img src="..."> */
    public function url(): string
    {
        return Storage::url($this->path);
    }

    /** Delete the actual file from storage when this model is deleted */
    protected static function booted(): void
    {
        static::deleting(function (CarImage $image) {
            Storage::delete($image->path);
        });
    }
}