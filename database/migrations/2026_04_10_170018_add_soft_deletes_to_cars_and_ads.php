<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Cars ──────────────────────────────────────────────────────
        Schema::table('cars', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // ── Fix orders.car_id — was cascadeOnDelete which would wipe
        //    orders when a car is deleted. Switch to nullOnDelete so
        //    orders survive and just lose the live car reference.
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreignId('car_id')->nullable()->change();
            $table->foreign('car_id')->references('id')->on('cars')->nullOnDelete();
        });

        // ── Advertisements ────────────────────────────────────────────
        Schema::table('advertisements', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->foreignId('car_id')->nullable(false)->change();
            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnDelete();
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};