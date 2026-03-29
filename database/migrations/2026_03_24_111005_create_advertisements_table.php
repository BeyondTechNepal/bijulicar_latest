<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();

            // The business user who created this ad
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // Where the ad points to (optional — can link to a car listing or external URL)
            $table->foreignId('car_id')->nullable()->constrained('cars')->nullOnDelete();
            $table->string('link_url')->nullable();

            // Banner image stored in public/ad-banners
            $table->string('image')->nullable();

            // Placement: where on the site this ad appears
            $table->enum('placement', ['home', 'marketplace', 'sidebar'])->default('marketplace');

            // Scheduling
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();

            // Active / inactive toggle
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};