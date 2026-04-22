<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // listing_type determines whether the car is for sale, rent, or both.
            // Default is 'sale' so all existing listings remain unaffected.
            $table->enum('listing_type', ['sale', 'rent', 'both'])
                  ->default('sale')
                  ->after('status');

            // Rental pricing and constraints — nullable so sale-only listings
            // never need to populate these.
            $table->unsignedInteger('rent_price_per_day')
                  ->nullable()
                  ->after('listing_type')
                  ->comment('Daily rental rate in NRs');

            $table->unsignedTinyInteger('rent_min_days')
                  ->nullable()
                  ->default(1)
                  ->after('rent_price_per_day')
                  ->comment('Minimum rental duration in days');

            $table->unsignedTinyInteger('rent_max_days')
                  ->nullable()
                  ->after('rent_min_days')
                  ->comment('Maximum rental duration in days — null means no limit');

            $table->unsignedInteger('rent_deposit')
                  ->nullable()
                  ->after('rent_max_days')
                  ->comment('Refundable security deposit in NRs — null means no deposit required');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'listing_type',
                'rent_price_per_day',
                'rent_min_days',
                'rent_max_days',
                'rent_deposit',
            ]);
        });
    }
};