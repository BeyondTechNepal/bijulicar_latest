<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news_article', function (Blueprint $table) {
            $table->string('quote')->nullable();
            $table->string('title2')->nullable();
            $table->longText('content_html2')->nullable();
            $table->json('content_images2')->nullable();
            $table->string('title3')->nullable();
            $table->longText('content_html3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_article', function (Blueprint $table) {
            //
        });
    }
};
