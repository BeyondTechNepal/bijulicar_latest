<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ev_listings', function (Blueprint $table) {
            $table->text('about_text')->nullable()->after('dimensions');
            $table->json('key_features')->nullable()->after('about_text');
            $table->unsignedInteger('total_airbags')->nullable()->after('safety_rating');
        });
    }

    public function down(): void
    {
        Schema::table('ev_listings', function (Blueprint $table) {
            $table->dropColumn(['about_text', 'key_features', 'total_airbags']);
        });
    }
};