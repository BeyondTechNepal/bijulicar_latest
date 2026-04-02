<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Expand the placement enum with all new slots
        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'news_sidebar',
            'news_detail_sidebar',
            'business_banner',
            'car_detail_sidebar',
            'business_profile'
        ) NOT NULL DEFAULT 'marketplace'");

        // Step 2 — Add priority column for Option B bidding system
        // 0 = Standard (free), 1 = Featured, 2 = Premium
        // Higher priority ads are shown first within the same placement slot
        Schema::table('advertisements', function (Blueprint $table) {
            $table->unsignedTinyInteger('priority')->default(0)->after('is_active')
                  ->comment('0=Standard, 1=Featured, 2=Premium');
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('priority');
        });

        DB::statement("ALTER TABLE advertisements MODIFY COLUMN placement ENUM(
            'home',
            'marketplace',
            'sidebar'
        ) NOT NULL DEFAULT 'marketplace'");
    }
};