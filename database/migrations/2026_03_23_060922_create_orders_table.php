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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();

            // Status lifecycle: pending → confirmed → completed | cancelled
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');

            $table->unsignedBigInteger('total_price');  // snapshot of car price at time of order (NRs)
            $table->text('notes')->nullable();           // optional message from buyer to seller

            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamps();

            // A buyer cannot order the same car twice
            $table->unique(['buyer_id', 'car_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};