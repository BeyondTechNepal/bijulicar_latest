<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Advertisement scheduler ────────────────────────────────────────────────
// Runs once per day at midnight Nepal time (UTC+5:45).
// - Activates ads whose starts_at has arrived (is_active false → true)
// - Deactivates ads whose ends_at has passed (is_active true → false)
// - Sends notification emails to affected business owners
// - Busts the home-page ad cache
Schedule::command('ads:sync-status')
    ->dailyAt('00:05')          // 5 minutes past midnight to avoid exact-midnight races
    ->timezone('Asia/Kathmandu')
    ->withoutOverlapping()      // safe guard: skip if previous run hasn't finished
    ->runInBackground();