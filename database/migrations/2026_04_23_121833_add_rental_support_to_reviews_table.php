<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The unique index reviews_buyer_id_car_id_unique is used as the backing
        // index for reviews_buyer_id_foreign (buyer_id → users). Drop that FK first.
        DB::statement('ALTER TABLE `reviews` DROP FOREIGN KEY `reviews_buyer_id_foreign`');

        // A previous failed migration attempt already added car_rental_id — drop it
        // cleanly before we re-add it so this migration is idempotent.
        if (Schema::hasColumn('reviews', 'car_rental_id')) {
            DB::statement('ALTER TABLE `reviews` DROP FOREIGN KEY `reviews_car_rental_id_foreign`');
            Schema::table('reviews', fn(Blueprint $t) => $t->dropColumn('car_rental_id'));
        }

        // Drop the unique — its only dependent FK is now gone.
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['buyer_id', 'car_id']);
        });

        // Rebuild everything cleanly in one statement.
        Schema::table('reviews', function (Blueprint $table) {
            // Restore buyer_id FK (no longer tied to the old composite unique)
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();

            // Add car_rental_id column + FK
            $table->foreignId('car_rental_id')
                ->nullable()
                ->after('seller_id')
                ->constrained('car_rentals')
                ->nullOnDelete();

            // New unique: one purchase review per car (NULL), one review per rental booking
            $table->unique(['buyer_id', 'car_id', 'car_rental_id'], 'reviews_buyer_car_rental_unique');
        });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `reviews` DROP FOREIGN KEY `reviews_buyer_id_foreign`');
        DB::statement('ALTER TABLE `reviews` DROP FOREIGN KEY `reviews_car_rental_id_foreign`');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_buyer_car_rental_unique');
            $table->dropColumn('car_rental_id');

            // Restore original state
            $table->unique(['buyer_id', 'car_id']);
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};