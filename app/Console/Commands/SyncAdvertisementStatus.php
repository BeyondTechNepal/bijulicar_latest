<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\HomeController;

class SyncAdvertisementStatus extends Command
{
    protected $signature   = 'ads:sync-status';
    protected $description = 'Activate scheduled ads whose start_date has arrived, and deactivate ads that have passed their end_date.';

    public function handle(NotificationService $notifications): int
    {
        $today = now()->toDateString();

        // ── 1. Activate: published ads where today >= starts_at but is_active is still false ──
        // This handles the "future start date" scenario — payment confirmed early,
        // ad was published but held back until the scheduled start.
        $activated = Advertisement::where('status', 'published')
            ->where('is_active', false)
            ->whereDate('starts_at', '<=', $today)
            ->whereDate('ends_at', '>=', $today)
            ->get();

        foreach ($activated as $ad) {
            $ad->update(['is_active' => true]);

            // Notify the business owner that their ad is now showing
            $notifications->adWentLive($ad);

            $this->info("Activated ad #{$ad->id} — \"{$ad->title}\"");
        }

        // ── 2. Deactivate: ads whose end_date has passed ───────────────────
        $deactivated = Advertisement::where('status', 'published')
            ->where('is_active', true)
            ->whereDate('ends_at', '<', $today)
            ->get();

        foreach ($deactivated as $ad) {
            $ad->update(['is_active' => false]);

            // Notify the business owner that their ad run has ended
            $notifications->adExpired($ad);

            $this->info("Deactivated ad #{$ad->id} — \"{$ad->title}\" (expired)");
        }

        // ── 3. Bust home-page ad cache so changes are immediately visible ──
        if ($activated->isNotEmpty() || $deactivated->isNotEmpty()) {
            Cache::forget(HomeController::CACHE_HOME_ADS);
        }

        $this->info(sprintf(
            'Done. Activated: %d  |  Deactivated: %d',
            $activated->count(),
            $deactivated->count()
        ));

        return Command::SUCCESS;
    }
}