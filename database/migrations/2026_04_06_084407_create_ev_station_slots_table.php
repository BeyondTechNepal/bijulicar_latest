<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ev_station_slots', function (Blueprint $table) {
            $table->id();

            // The EV station owner who owns this slot
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Slot number within the station (e.g. 1, 2, 3 ... total_slots)
            $table->unsignedTinyInteger('slot_number');

            // Current status of this charging port
            $table->enum('status', ['available', 'occupied'])->default('available');

            // If occupied, when is it expected to be free
            $table->dateTime('free_at')->nullable();

            // The customer currently using this slot (nullable — manual management for now)
            $table->foreignId('occupied_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Each station can only have one record per slot number
            $table->unique(['user_id', 'slot_number']);

            $table->index('user_id');
            $table->index('status');
        });

        // Add total_slots to new_locations so the map knows the station capacity
        Schema::table('new_locations', function (Blueprint $table) {
            $table->unsignedTinyInteger('total_slots')->default(0)->after('is_active');
            $table->boolean('accepts_walkins')->default(true)->after('total_slots');
        });
    }

    public function down(): void
    {
        Schema::table('new_locations', function (Blueprint $table) {
            $table->dropColumn(['total_slots', 'accepts_walkins']);
        });

        Schema::dropIfExists('ev_station_slots');
    }
};