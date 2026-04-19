<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Expand the orders.status enum to include 'sold_out'.
     *
     * sold_out is set automatically on all pending orders for a car when
     * another order for the same car (with stock_quantity = 1) is confirmed
     * by the seller. It signals to the buyer that the car is gone and their
     * order has no chance of being fulfilled.
     *
     * Lifecycle:  pending → sold_out  (auto, triggered by a sibling confirmation)
     *             pending → confirmed → completed | cancelled
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status
                ENUM('pending','confirmed','completed','cancelled','sold_out')
                NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Remove any sold_out rows first so the rollback doesn't fail on
        // the stricter enum.  In practice you would want a data-migration
        // to decide what to do with those rows; here we cancel them.
        DB::statement("UPDATE orders SET status = 'cancelled' WHERE status = 'sold_out'");

        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status
                ENUM('pending','confirmed','completed','cancelled')
                NOT NULL DEFAULT 'pending'
        ");
    }
};