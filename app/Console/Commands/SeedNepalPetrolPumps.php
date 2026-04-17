<?php

namespace App\Console\Commands;

use App\Models\PetrolPump;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Fetches ALL petrol pumps in Nepal in ONE Overpass query using the
 * country area filter — no tile grid, no rate-limit torture.
 *
 * Run once:  php artisan pumps:seed-nepal
 * Monthly:   Schedule::command('pumps:seed-nepal --force')->monthly();
 */
class SeedNepalPetrolPumps extends Command
{
    protected $signature   = 'pumps:seed-nepal {--force : Re-fetch even if recently seeded}';
    protected $description = 'Seed all Nepal petrol pumps via a single Overpass area query';

    // Nepal relation ID in OpenStreetMap
    private const NEPAL_AREA_ID = 3600184633; // = 3600000000 + relation 184633

    private const ENDPOINTS = [
        'https://overpass-api.de/api/interpreter',
        'https://overpass.kumi.systems/api/interpreter',
        'https://maps.mail.ru/osm/tools/overpass/api/interpreter',
    ];

    public function handle(): int
    {
        // Guard: skip if seeded recently (within 30 days) unless --force
        if (!$this->option('force')) {
            $last = DB::table('petrol_pump_cache_tiles')
                ->where('tile_lat', 0)->where('tile_lng', 0)
                ->value('fetched_at');
            if ($last && now()->diffInDays($last) < 30) {
                $this->info("Already seeded {$last}. Use --force to re-fetch.");
                return self::SUCCESS;
            }
        }

        // Single query — fetch all nodes/ways tagged amenity=fuel inside Nepal
        $query = <<<'OQL'
[out:json][timeout:120];
area(3600184633)->.nepal;
(
  node["amenity"="fuel"](area.nepal);
  way["amenity"="fuel"](area.nepal);
);
out center;
OQL;

        $this->info('Querying Overpass for all petrol pumps in Nepal (single request)...');
        $response = null;

        foreach (self::ENDPOINTS as $endpoint) {
            try {
                $response = Http::connectTimeout(15)
                    ->timeout(150)   // give it 2.5 min for a country-wide query
                    ->asForm()
                    ->post($endpoint, ['data' => $query]);

                if ($response->successful()) {
                    $this->info("Success via {$endpoint}");
                    break;
                }

                $this->warn("HTTP {$response->status()} from {$endpoint} — trying next.");
                $response = null;

            } catch (\Exception $e) {
                $this->warn("Failed {$endpoint}: " . $e->getMessage());
                $response = null;
            }
        }

        if (!$response || !$response->successful()) {
            $this->error('All Overpass endpoints failed. Try again later.');
            return self::FAILURE;
        }

        $elements = $response->json('elements', []);
        $this->info('Elements returned: ' . count($elements));

        if (empty($elements)) {
            $this->warn('No pumps returned. The query may have timed out on Overpass side.');
            return self::FAILURE;
        }

        // Clear old data and re-insert
        if ($this->option('force')) {
            PetrolPump::truncate();
            DB::table('petrol_pump_cache_tiles')->truncate();
        }

        $bar = $this->output->createProgressBar(count($elements));
        $bar->start();

        $inserted = 0;
        foreach ($elements as $el) {
            $lat = $el['lat'] ?? ($el['center']['lat'] ?? null);
            $lng = $el['lon'] ?? ($el['center']['lon'] ?? null);

            if (!$lat || !$lng) { $bar->advance(); continue; }

            // Hard guard: skip anything outside Nepal's bounding box
            if ($lat < 26.35 || $lat > 30.45 || $lng < 80.05 || $lng > 88.20) {
                $bar->advance();
                continue;
            }

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
            $inserted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Mark as seeded using tile (0,0) as a sentinel record
        DB::table('petrol_pump_cache_tiles')->upsert(
            [['tile_lat' => 0, 'tile_lng' => 0, 'fetched_at' => now()]],
            ['tile_lat', 'tile_lng'],
            ['fetched_at']
        );

        $this->info("Done! {$inserted} pumps inserted/updated (Nepal only).");
        return self::SUCCESS;
    }
}