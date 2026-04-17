<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetrolPump extends Model
{
    protected $fillable = [
        'osm_id',
        'name',
        'latitude',
        'longitude',
        'brand',
        'opening_hours',
        'phone',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get pumps within a bounding box (fast index scan, no full-table scan).
     * Call this instead of a radius filter — the controller does the exact
     * Haversine check after fetching the bounding box.
     */
    public static function inBoundingBox(
        float $lat, float $lng, float $radiusKm
    ) {
        // 1 degree lat  ≈ 111 km
        // 1 degree lng  ≈ 111 km × cos(lat)
        $latDelta = $radiusKm / 111.0;
        $lngDelta = $radiusKm / (111.0 * cos(deg2rad($lat)));

        return static::query()
            ->whereBetween('latitude',  [$lat - $latDelta, $lat + $latDelta])
            ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta])
            ->get(['id', 'osm_id', 'name', 'latitude', 'longitude', 'brand', 'opening_hours', 'phone']);
    }
}