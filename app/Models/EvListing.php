<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvListing extends Model
{
    protected $fillable = [
        'brand', 'model', 'variant', 'slug',
        'price', 'battery_kwh', 'motor_kw', 'range_km', 'range_test_standard',
        'drivetrain', 'seating_capacity', 'ground_clearance_mm', 'boot_space_litres',
        'charging_time', 'safety_rating', 'total_airbags', 'dimensions',
        'about_text', 'key_features',
        'image_url', 'gallery_urls',
        'source_url', 'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'gallery_urls'   => 'array',
            'key_features'   => 'array',
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

    /**
     * How many used units of THIS car are currently for sale on our own
     * marketplace — matched by brand + model, case-insensitively (EV Nepal
     * and our marketplace sellers don't always type casing the same way).
     */
    public function usedMarketplaceListings()
    {
        return Car::query()
            ->where('condition', 'used')
            ->where('status', 'available')
            ->whereRaw('LOWER(brand) = ?', [strtolower($this->brand)])
            ->whereRaw('LOWER(model) LIKE ?', ['%' . strtolower($this->model) . '%']);
    }

    public function usedListingsCount(): int
    {
        return $this->usedMarketplaceListings()->count();
    }
}