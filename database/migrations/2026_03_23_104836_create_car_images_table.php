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
        Schema::create('car_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();

            $table->string('path');                          // storage path e.g. car-images/abc.jpg
            $table->string('alt')->nullable();               // alt text for accessibility
            $table->unsignedTinyInteger('sort_order')->default(0); // controls display order
            $table->boolean('is_primary')->default(false);  // one image is the cover photo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_images');
    }
};