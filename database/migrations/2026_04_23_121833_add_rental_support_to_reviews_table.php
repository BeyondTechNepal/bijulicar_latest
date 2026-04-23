<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Link to the rental that triggered this review (null = purchase review)
            $table->foreignId('car_rental_id')
                ->nullable()
                ->after('seller_id')
                ->constrained('car_rentals')
                ->nullOnDelete();

            // Drop the old unique constraint (buyer_id, car_id) — a buyer can now
            // leave one review per purchase AND one per rental on the same car.
            $table->dropUnique(['buyer_id', 'car_id']);

            // New constraint: one review per buyer per car per source.
            // car_rental_id IS NULL  → purchase review  (only one allowed)
            // car_rental_id NOT NULL → rental review    (one per rental booking)
            // MySQL partial unique isn't available via Blueprint, so we use a
            // regular unique across all three columns — NULL values are treated
            // as distinct by MySQL so two NULLs on the same (buyer, car) pair
            // would still collide; we handle that deduplication in the controller.
            $table->unique(['buyer_id', 'car_id', 'car_rental_id'], 'reviews_buyer_car_rental_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_buyer_car_rental_unique');
            $table->dropForeign(['car_rental_id']);
            $table->dropColumn('car_rental_id');
            $table->unique(['buyer_id', 'car_id']);
        });
    }
};