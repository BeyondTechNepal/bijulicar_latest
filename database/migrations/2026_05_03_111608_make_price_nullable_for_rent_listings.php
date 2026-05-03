<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow price to be null for rent-only listings.
     *
     * The original create_cars_table migration defined price as
     * unsignedBigInteger (NOT NULL). Now that listing_type='rent'
     * listings legitimately have no sale price, we relax that constraint.
     *
     * Existing rows are unaffected — they already have a price value.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Before reverting, ensure no rent-only rows would be broken.
        // You may want to set a default price on those rows first.
        Schema::table('cars', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->nullable(false)->change();
        });
    }
};