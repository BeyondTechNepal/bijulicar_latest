<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Add the new value to the ENUM (keep old one so existing rows stay valid)
        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_sidebar',
            'car_detail_horizontal',
            'business_profile'
        ) NOT NULL DEFAULT 'marketplace'");

        // Step 2 — Migrate existing rows to the new placement key
        DB::table('advertisements')
            ->where('placement', 'car_detail_sidebar')
            ->update(['placement' => 'car_detail_horizontal']);

        // Step 3 — Remove the old value from the ENUM now that no rows use it
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

    public function down(): void
    {
        // Step 1 — Add old value back to ENUM
        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_sidebar',
            'car_detail_horizontal',
            'business_profile'
        ) NOT NULL DEFAULT 'marketplace'");

        // Step 2 — Revert rows back to old placement key
        DB::table('advertisements')
            ->where('placement', 'car_detail_horizontal')
            ->update(['placement' => 'car_detail_sidebar']);

        // Step 3 — Remove the new value from the ENUM
        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_sidebar',
            'business_profile'
        ) NOT NULL DEFAULT 'marketplace'");
    }
};