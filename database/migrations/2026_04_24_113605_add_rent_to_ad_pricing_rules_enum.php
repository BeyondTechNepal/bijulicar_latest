<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Add 'rent' to the advertisements placement ENUM
        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'rent',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_horizontal',
            'business_profile'
        ) NOT NULL DEFAULT 'marketplace'");

        // Step 2 — Add 'rent' to the ad_pricing_rules placement ENUM
        DB::statement("ALTER TABLE ad_pricing_rules MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'rent',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_horizontal',
            'business_profile'
        ) NOT NULL");

        // Step 3 — Seed 3 pricing tiers for the rent placement
        $now = now();

        $tiers = [
            0 => ['price_per_day' => 200,  'min_days' => 7],  // Standard
            1 => ['price_per_day' => 500,  'min_days' => 7],  // Featured
            2 => ['price_per_day' => 1000, 'min_days' => 7],  // Premium
        ];

        foreach ($tiers as $priority => $config) {
            DB::table('ad_pricing_rules')->insertOrIgnore([
                'placement'     => 'rent',
                'priority'      => $priority,
                'price_per_day' => $config['price_per_day'],
                'min_days'      => $config['min_days'],
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Remove the rent pricing rows
        DB::table('ad_pricing_rules')->where('placement', 'rent')->delete();

        // Remove 'rent' from ad_pricing_rules ENUM
        DB::statement("ALTER TABLE ad_pricing_rules MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_horizontal',
            'business_profile'
        ) NOT NULL");

        // Remove 'rent' from advertisements ENUM
        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_horizontal',
            'business_profile'
        ) NOT NULL DEFAULT 'marketplace'");
    }
};