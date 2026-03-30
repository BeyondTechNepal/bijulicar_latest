<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Buyer's contact details captured at order time
            // These are snapshots — independent of whether the user later changes their profile
            $table->string('buyer_name')->nullable()->after('notes');
            $table->string('buyer_phone', 20)->nullable()->after('buyer_name');
            $table->string('buyer_email')->nullable()->after('buyer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['buyer_name', 'buyer_phone', 'buyer_email']);
        });
    }
};