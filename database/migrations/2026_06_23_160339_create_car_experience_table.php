<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_experiences', function (Blueprint $table) {
            $table->id();

            // --- Author ---
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // --- Car link (optional) ---
            // Null when the experience is about a car outside BijuliCar.
            // Set to NULL (not deleted) when the linked car is soft-deleted
            // so the experience stays visible with external_car_name as fallback.
            $table->foreignId('car_id')
                  ->nullable()
                  ->constrained('cars')
                  ->nullOnDelete();

            // Used when car_id is null — free-text car name entered by the user.
            // Also kept as display fallback if car_id later becomes null via nullOnDelete.
            $table->string('external_car_name')
                  ->nullable()
                  ->comment('Car name when not linked to a BijuliCar listing, or snapshot if listing is removed');

            // --- Experience content ---
            $table->string('title');

            // Optional short context: e.g. "Kathmandu to Pokhara road trip"
            $table->string('trip_context')->nullable();

            $table->text('body');

            // rental   = car was rented
            // purchase = car was purchased
            // general  = general driving / ownership experience
            $table->enum('experience_type', ['rental', 'purchase', 'general'])
                  ->default('general');

            // --- Moderation ---
            // pending  → awaiting admin review
            // approved → visible publicly
            // rejected → hidden; admin_note explains why
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->text('admin_note')->nullable()
                  ->comment('Rejection reason or admin remark');

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_experiences');
    }
};