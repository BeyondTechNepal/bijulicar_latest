<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    DB::statement("ALTER TABLE ev_station_slots MODIFY COLUMN status 
        ENUM('available','pending','booked','occupied') 
        NOT NULL DEFAULT 'available'");
}

public function down(): void
{
    DB::statement("UPDATE ev_station_slots SET status = 'pending' WHERE status = 'booked'");
    DB::statement("ALTER TABLE ev_station_slots MODIFY COLUMN status 
        ENUM('available','pending','occupied') 
        NOT NULL DEFAULT 'available'");
}
};
