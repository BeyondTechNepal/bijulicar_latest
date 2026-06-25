<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ev_listings', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('variant')->nullable();
            $table->string('slug')->unique();

            $table->unsignedBigInteger('price')->nullable();      // in NPR
            $table->decimal('battery_kwh', 6, 2)->nullable();
            $table->unsignedInteger('motor_kw')->nullable();
            $table->unsignedInteger('range_km')->nullable();
            $table->string('range_test_standard')->nullable();    // WLTP / NEDC etc.
            $table->string('drivetrain')->nullable();              // FWD / RWD / AWD
            $table->unsignedInteger('seating_capacity')->nullable();
            $table->unsignedInteger('ground_clearance_mm')->nullable();
            $table->unsignedInteger('boot_space_litres')->nullable();
            $table->string('charging_time')->nullable();
            $table->string('safety_rating')->nullable();
            $table->string('dimensions')->nullable();              // "4290 x 1770 x 1570 mm"

            $table->string('image_url')->nullable();
            $table->json('gallery_urls')->nullable();

            $table->string('source_url')->nullable();              // original ev-nepal.com page
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            $table->unique(['brand', 'model', 'variant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ev_listings');
    }
};