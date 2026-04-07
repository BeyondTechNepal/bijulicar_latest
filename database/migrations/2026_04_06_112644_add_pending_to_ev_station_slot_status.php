<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * If you already ran the original ev_station_slots migration,
     * run this one to add 'pending' to the status enum.
     *
     * If you haven't run any migrations yet, the updated
     * 2026_04_06_000001_create_ev_station_slots_table.php already
     * includes 'pending' — skip this file.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE ev_station_slots MODIFY COLUMN status ENUM('available','pending','occupied') NOT NULL DEFAULT 'available'");
    }

    public function down(): void
    {
        // Reset any pending slots to available before removing the value
        DB::statement("UPDATE ev_station_slots SET status = 'available' WHERE status = 'pending'");
        DB::statement("ALTER TABLE ev_station_slots MODIFY COLUMN status ENUM('available','occupied') NOT NULL DEFAULT 'available'");
    }
};