<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_experiences', function (Blueprint $table) {
            // Optional override for the displayed author name.
            // When set (e.g. admin posting on behalf of someone),
            // this is shown instead of user->name on the frontend.
            $table->string('author_name')
                  ->nullable()
                  ->after('user_id')
                  ->comment('Display name override — used when admin posts on behalf of a real person');
        });
    }

    public function down(): void
    {
        Schema::table('car_experiences', function (Blueprint $table) {
            $table->dropColumn('author_name');
        });
    }
};