<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvListing extends Model
{
    protected $fillable = [
        'brand', 'model', 'variant', 'slug',
        'price', 'battery_kwh', 'motor_kw', 'range_km', 'range_test_standard',
        'drivetrain', 'seating_capacity', 'ground_clearance_mm', 'boot_space_litres',
        'charging_time', 'safety_rating', 'dimensions',
        'image_url', 'gallery_urls',
        'source_url', 'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'gallery_urls'   => 'array',
            'last_synced_at' => 'datetime',
            'battery_kwh'    => 'float',
        ];
    }

    // Lets you do route('ev-prices.show', $listing) and have Laravel
    // resolve by slug instead of id automatically.
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function displayName(): string
    {
        return trim("{$this->brand} {$this->model} " . ($this->variant ?: ''));
    }

    public function formattedPrice(): string
    {
        return $this->price ? 'Rs. ' . number_format($this->price) : 'Price on request';
    }
}