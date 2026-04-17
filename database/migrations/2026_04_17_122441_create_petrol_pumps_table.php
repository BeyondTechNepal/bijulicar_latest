<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petrol_pumps', function (Blueprint $table) {
            $table->id();
            $table->string('osm_id')->unique()->index();   // OpenStreetMap node/way ID
            $table->string('name')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('brand')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();                          // created_at = first fetched

            // Spatial-ish index: lat/lng range queries are fast with this
            $table->index(['latitude', 'longitude']);
        });

        // Tracks which geo-grid cells have been fetched, so we don't re-fetch
        Schema::create('petrol_pump_cache_tiles', function (Blueprint $table) {
            $table->id();
            // We divide Nepal into ~0.1° × 0.1° tiles (~11km × 11km)
            $table->decimal('tile_lat', 6, 2);   // e.g. 27.60
            $table->decimal('tile_lng', 6, 2);   // e.g. 85.30
            $table->timestamp('fetched_at');
            $table->unique(['tile_lat', 'tile_lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petrol_pumps');
        Schema::dropIfExists('petrol_pump_cache_tiles');
    }
};