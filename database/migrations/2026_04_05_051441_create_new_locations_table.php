<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('new_locations', function (Blueprint $table) {
        $table->id();
        // Connects to the Breeze User
        $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
        
        // Tells the map which tab to put the icon in ('ev' or 'garage')
        $table->string('type'); 
        
        $table->string('address')->nullable();
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        
        // Important: Only show on map if Admin has approved the business
        $table->boolean('is_active')->default(false); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_locations');
    }
};
