<?php

namespace App\Services;

use App\Models\PetrolPump;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OverpassService
{
    // Tile size in degrees (~11 km). Covers Nepal well.
    // Increase to 0.2 for sparser areas; decrease for dense cities.
    private const TILE_SIZE = 0.2;

    // Re-fetch a tile after 30 days (petrol pumps don't move often)
    private const TILE_TTL_DAYS = 30;

    // Overpass mirror list — rotate if primary is down/slow
    private const ENDPOINTS = [
        'https://overpass-api.de/api/interpreter',
        'https://overpass.kumi.systems/api/interpreter',
        // 'https://maps.mail.ru/osm/tools/overpass/api/interpreter',
    ];

    /**
     * Main public method.
     * Returns array of pumps within $radiusKm of ($lat, $lng).
     * Will fetch from Overpass only for tiles NOT yet in DB.
     */
    public function getPumpsNear(float $lat, float $lng, float $radiusKm): array
    {
        // 1. Determine which tiles are needed
        $tiles = $this->tilesForRadius($lat, $lng, $radiusKm);

        // 2. Fetch any missing / stale tiles from Overpass (may be zero)
        $this->fetchMissingTiles($tiles);

        // 3. Query DB — fast bounding-box index scan
        $pumps = PetrolPump::inBoundingBox($lat, $lng, $radiusKm);

        // 4. Exact Haversine filter (bounding box includes corners outside radius)
        return $pumps->filter(function ($p) use ($lat, $lng, $radiusKm) {
            return $this->haversineKm($lat, $lng, $p->latitude, $p->longitude) <= $radiusKm;
        })->values()->toArray();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Return the set of tile corners (rounded to TILE_SIZE) that overlap
     * a circle of $radiusKm centred on ($lat, $lng).
     */
    private function tilesForRadius(float $lat, float $lng, float $radiusKm): array
    {
        $ts       = self::TILE_SIZE;
        $latDelta = $radiusKm / 111.0;
        $lngDelta = $radiusKm / (111.0 * max(cos(deg2rad($lat)), 0.01));

        $minLat = floor(($lat - $latDelta) / $ts) * $ts;
        $maxLat = floor(($lat + $latDelta) / $ts) * $ts;
        $minLng = floor(($lng - $lngDelta) / $ts) * $ts;
        $maxLng = floor(($lng + $lngDelta) / $ts) * $ts;

        $tiles = [];
        $latSteps = (int) round(($maxLat - $minLat) / $ts);
        $lngSteps = (int) round(($maxLng - $minLng) / $ts);

        for ($i = 0; $i <= $latSteps; $i++) {
            for ($j = 0; $j <= $lngSteps; $j++) {
                $tlat = round($minLat + $i * $ts, 2);
                $tlng = round($minLng + $j * $ts, 2);
                $tiles[] = ['lat' => $tlat, 'lng' => $tlng];
            }
        }
        return $tiles;
    }

    /**
     * For each tile, check if it's already in DB and fresh.
     * If not, fetch from Overpass and upsert into petrol_pumps.
     */
    private function fetchMissingTiles(array $tiles): void
    {
        $staleAfter = now()->subDays(self::TILE_TTL_DAYS);

        foreach ($tiles as $tile) {
            $tl = round($tile['lat'], 2);
            $tg = round($tile['lng'], 2);

            // Check cache tile table
            $cached = DB::table('petrol_pump_cache_tiles')
                ->where('tile_lat', $tl)
                ->where('tile_lng', $tg)
                ->first();

            if ($cached && $cached->fetched_at > $staleAfter->toDateTimeString()) {
                continue; // Fresh — skip Overpass call
            }

            // Build bounding box for this tile
            $south = $tl;
            $north = round($tl + self::TILE_SIZE, 2);
            $west  = $tg;
            $east  = round($tg + self::TILE_SIZE, 2);

            $this->fetchAndStoreTile($south, $north, $west, $east, $tl, $tg);
        }
    }

    /**
     * Call Overpass for a single tile bounding box, store results in DB.
     */
    private function fetchAndStoreTile(
        float $south, float $north, float $west, float $east,
        float $tileLat, float $tileLng
    ): void {

        $south = number_format($south, 2, '.', '');
        $north = number_format($north, 2, '.', '');
        $west  = number_format($west,  2, '.', '');
        $east  = number_format($east,  2, '.', '');
        $query = "[out:json][timeout:30];"
               . "(node[\"amenity\"=\"fuel\"]({$south},{$west},{$north},{$east});"
               . "way[\"amenity\"=\"fuel\"]({$south},{$west},{$north},{$east}););"
               . "out center;";
        Log::info("Overpass query for tile ({$tileLat},{$tileLng}): " . $query);
        $response = null;

        foreach (self::ENDPOINTS as $endpoint) {
    try {
        $response = Http::connectTimeout(10)->timeout(60)
            ->asForm()
            ->post($endpoint, ['data' => $query]);

        if ($response->successful()) {
            Log::info("Overpass success via {$endpoint}");
            break;
        }

        Log::warning("Overpass {$endpoint} returned HTTP {$response->status()} — trying next mirror.");
        $response = null; // force next mirror

    } catch (\Exception $e) {
        Log::warning("Overpass endpoint {$endpoint} failed: " . $e->getMessage());
        $response = null;
    }
}

        if (!$response || !$response->successful()) {
            Log::error("All Overpass endpoints failed for tile ({$tileLat},{$tileLng})");
            return;
        }

        $elements = $response->json('elements', []);

        // Bulk upsert — ignores duplicates via osm_id unique index
        foreach ($elements as $el) {
            $lat = $el['lat'] ?? ($el['center']['lat'] ?? null);
            $lng = $el['lon'] ?? ($el['center']['lon'] ?? null);

            if (!$lat || !$lng) continue;

            // if ($lat < 26.35 || $lat > 30.45 || $lng < 80.05 || $lng > 88.20) {
            //     continue; // skip non-Nepal results
            // }
            $tags = $el['tags'] ?? [];

            PetrolPump::updateOrCreate(
                ['osm_id' => (string) $el['id']],
                [
                    'name'          => $tags['name'] ?? $tags['brand'] ?? null,
                    'latitude'      => $lat,
                    'longitude'     => $lng,
                    'brand'         => $tags['brand'] ?? null,
                    'opening_hours' => $tags['opening_hours'] ?? null,
                    'phone'         => $tags['phone'] ?? $tags['contact:phone'] ?? null,
                ]
            );
        }

        // Mark tile as fetched (upsert)
        DB::table('petrol_pump_cache_tiles')->upsert(
            [['tile_lat' => $tileLat, 'tile_lng' => $tileLng, 'fetched_at' => now()]],
            ['tile_lat', 'tile_lng'],
            ['fetched_at']
        );

        Log::info("Fetched " . count($elements) . " pumps for tile ({$tileLat},{$tileLng})");
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R  = 6371;
        $dL = deg2rad($lat2 - $lat1);
        $dG = deg2rad($lng2 - $lng1);
        $a  = sin($dL / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dG / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}