<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            // Owner — seller or business user
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

            // Core identity
            $table->string('make');                      // e.g. Tesla, BYD
            $table->string('model');                     // e.g. Model 3, Atto 3
            $table->unsignedSmallInteger('year');
            $table->string('variant')->nullable();       // e.g. Long Range

            // Drivetrain
            $table->enum('drivetrain', ['ev', 'hybrid', 'petrol', 'diesel'])->default('ev');

            // Specs
            $table->unsignedInteger('mileage')->default(0);     // km
            $table->unsignedInteger('range_km')->nullable();    // EV range
            $table->unsignedInteger('battery_kwh')->nullable(); // EV battery size
            $table->string('color')->nullable();
            $table->enum('condition', ['new', 'used', 'certified'])->default('used');

            // Pricing
            $table->unsignedBigInteger('price');                // NRs
            $table->boolean('price_negotiable')->default(false);

            // Location & listing
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('primary_image')->nullable();
            $table->enum('status', ['available', 'sold', 'reserved', 'inactive'])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};