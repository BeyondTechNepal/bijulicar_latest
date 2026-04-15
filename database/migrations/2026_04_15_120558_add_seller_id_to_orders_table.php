<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Snapshot of the seller at the time the order was placed.
            // Stored directly on the order so that seller dashboards, revenue
            // queries, and order history all remain intact even after the
            // car listing is soft-deleted or its car_id is nulled out.
            $table->unsignedBigInteger('seller_id')->nullable()->after('buyer_id');
            $table->foreign('seller_id')->references('id')->on('users')->nullOnDelete();

            // Snapshot of the car's display name at order time.
            // Once a car is deleted, car_id becomes null and we lose the
            // human-readable name. This column preserves it for history.
            $table->string('car_snapshot_name')->nullable()->after('seller_id');
        });

        // Back-fill existing orders from their related car record.
        DB::statement('
            UPDATE orders
            INNER JOIN cars ON cars.id = orders.car_id
            SET orders.seller_id        = cars.seller_id,
                orders.car_snapshot_name = CONCAT(cars.year, " ", cars.brand, " ", cars.model,
                                                  IF(cars.variant IS NOT NULL AND cars.variant != "",
                                                     CONCAT(" ", cars.variant), ""))
            WHERE orders.seller_id IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn(['seller_id', 'car_snapshot_name']);
        });
    }
};