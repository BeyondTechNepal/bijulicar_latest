<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Must drop FK constraints first — MySQL won't drop a unique
            // index that foreign keys depend on
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['car_id']);

            // Now safe to drop the unique index
            $table->dropUnique(['buyer_id', 'car_id']);

            // Re-add the foreign keys (without the unique constraint)
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['buyer_id']);
            $table->dropForeign(['car_id']);

            $table->unique(['buyer_id', 'car_id']);

            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnDelete();
        });
    }
};