<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->boolean('is_preorder')->default(false)->after('stock_quantity');
            $table->date('expected_arrival_date')->nullable()->after('is_preorder');
            $table->unsignedBigInteger('preorder_deposit')->nullable()->after('expected_arrival_date');
            // Add 'upcoming' to status enum
            // MySQL: use a raw statement to modify the enum
        });

        // Add 'upcoming' to the status enum (MySQL-safe way)
        DB::statement("ALTER TABLE cars MODIFY COLUMN status ENUM('available','sold','reserved','inactive','upcoming') NOT NULL DEFAULT 'available'");
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['is_preorder', 'expected_arrival_date', 'preorder_deposit']);
        });
        DB::statement("ALTER TABLE cars MODIFY COLUMN status ENUM('available','sold','reserved','inactive') NOT NULL DEFAULT 'available'");
    }
};