<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'ordered' to the status enum
        DB::statement("ALTER TABLE negotiations MODIFY COLUMN status ENUM(
            'pending_seller',
            'pending_buyer',
            'accepted',
            'declined',
            'expired',
            'cancelled',
            'ordered'
        ) NOT NULL DEFAULT 'pending_seller'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE negotiations MODIFY COLUMN status ENUM(
            'pending_seller',
            'pending_buyer',
            'accepted',
            'declined',
            'expired',
            'cancelled'
        ) NOT NULL DEFAULT 'pending_seller'");
    }
};