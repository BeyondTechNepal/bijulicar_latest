<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Change from unsignedInteger to decimal(5,1)
            // e.g. 77.4 kWh, 100.0 kWh — up to 9999.9
            $table->decimal('battery_kwh', 5, 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->unsignedInteger('battery_kwh')->nullable()->change();
        });
    }
};