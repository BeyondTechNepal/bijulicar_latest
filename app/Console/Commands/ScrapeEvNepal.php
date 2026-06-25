<?php

namespace App\Console\Commands;

use App\Models\EvListing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapeEvNepal extends Command
{
    /**
     * php artisan scrape:ev-nepal
     * php artisan scrape:ev-nepal --only=byd-dolphin-price-in-nepal
     */
    protected $signature = 'scrape:ev-nepal {--only=}';

    protected $description = 'Sync EV listings & specs from ev-nepal.com (partner feed) into the ev_listings table';

    protected string $baseUrl = 'https://www.ev-nepal.com';

    public function handle(): int
    {
        $slugs = $this->option('only')
            ? [$this->option('only')]
            : $this->discoverCarSlugs();

        $this->info('Found ' . count($slugs) . ' car page(s) to sync.');

        $saved = 0;

        foreach ($slugs as $slug) {
            if ($this->scrapeCarDetail($slug)) {
                $saved++;
            }
            sleep(1); // be polite to our partner's server
        }

        $this->info("Done. Saved/updated {$saved} listing(s).");
        return self::SUCCESS;
    }

    protected function discoverCarSlugs(): array
    {
        $html = $this->fetch("{$this->baseUrl}/ev-price-in-nepal");
        if (!$html) return [];

        preg_match_all('#/cars/([a-z0-9\-]+)#i', $html, $matches);

        return array_unique($matches[1]);
    }

    protected function scrapeCarDetail(string $slug): bool
    {
        $url = "{$this->baseUrl}/cars/{$slug}";
        $this->line("Syncing {$slug} ...");

        $html = $this->fetch($url);
        if (!$html) {
            $this->warn('  -> fetch failed, skipping');
            return false;
        }

        $variants = $this->extractVariants($html);
        $enriched = $this->extractEnrichedSpecs($html);

        if (empty($variants)) {
            $this->warn('  -> no variant data found, skipping');
            return false;
        }

        foreach ($variants as $car) {
            $slugForVariant = $this->uniqueSlug($car['brand'], $car['model'], $car['variant'] ?? '');

            EvListing::updateOrCreate(
                [
                    'brand'   => $car['brand'],
                    'model'   => $car['model'],
                    'variant' => $car['variant'] ?: null,
                ],
                [
                    'slug'                => $slugForVariant,
                    'price'               => $car['price'] ?? null,
                    'battery_kwh'         => $car['battery_kwh'] ?? null,
                    'motor_kw'            => $car['motor_kw'] ?? null,
                    'range_km'            => $car['claimed_range_kms'] ?? null,
                    'range_test_standard' => $car['range_test_standard'] ?? null,
                    'drivetrain'          => $enriched['Drive Type'] ?? null,
                    'seating_capacity'    => $this->numeric($enriched['Seating Capacity'] ?? null),
                    'ground_clearance_mm' => $this->numeric($enriched['Ground Clearance'] ?? null),
                    'boot_space_litres'   => $this->numeric($enriched['Boot Space'] ?? null),
                    'charging_time'       => $car['charging_time'] ?? null,
                    'safety_rating'       => $enriched['Safety Rating'] ?? null,
                    'dimensions'          => $enriched['Dimensions'] ?? null,
                    'image_url'           => $car['image'] ?? null,
                    'source_url'          => $url,
                    'last_synced_at'      => now(),
                ]
            );
        }

        $this->info('  -> saved ' . count($variants) . ' variant(s)');
        return true;
    }

    /**
     * Pull the clean "variants":[{...}] JSON embedded in the page's
     * Next.js data island, rather than scraping visible text.
     */
    protected function extractVariants(string $html): array
    {
        preg_match('/"variants":(\[\{.*?\}\]),"images"/s', $html, $matches);
        if (!isset($matches[1])) return [];

        return json_decode($matches[1], true) ?? [];
    }

    /**
     * The page also embeds a flat "currentCarEnriched": {"Drive Type":"FWD",...}
     * object — grab that too for the extra spec fields not in $variants.
     */
    protected function extractEnrichedSpecs(string $html): array
    {
        preg_match('/"currentCarEnriched":(\{.*?\}),"similarCars"/s', $html, $matches);
        if (!isset($matches[1])) return [];

        return json_decode($matches[1], true) ?? [];
    }

    /**
     * Build a slug, and if it collides with a DIFFERENT car (e.g. "E2" and
     * "E2+" both strip down to "e2"), append -2, -3, etc. until it's unique.
     * If the colliding row is itself the same brand+model+variant we're
     * about to update, that's fine — just reuse its existing slug.
     */
    protected function uniqueSlug(string $brand, string $model, string $variant): string
    {
        $base = Str::slug("{$brand}-{$model}-{$variant}");
        $slug = $base;
        $i = 2;

        while (true) {
            $existing = EvListing::where('slug', $slug)->first();

            if (!$existing) {
                return $slug;
            }

            // Same logical car (matched by brand/model/variant) — keep its slug.
            if ($existing->brand === $brand && $existing->model === $model && $existing->variant === ($variant ?: null)) {
                return $slug;
            }

            $slug = "{$base}-{$i}";
            $i++;
        }
    }

    protected function numeric(?string $raw): ?int
    {
        if (!$raw) return null;
        $cleaned = preg_replace('/[^0-9]/', '', $raw);
        return $cleaned !== '' ? (int) $cleaned : null;
    }

    protected function fetch(string $url): ?string
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; BijuliCarBot/1.0; partner-sync)',
        ])->get($url);

        if ($response->failed()) return null;

        // Unescape quotes once here, so every method downstream works
        // with plain JSON syntax.
        return str_replace('\"', '"', $response->body());
    }
}