<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();

            // --- Parties ---
            // car_id is nullable: set to NULL when the car is soft-deleted,
            // just like orders. car_snapshot_name is the safe display fallback.
            $table->foreignId('car_id')
                  ->nullable()
                  ->constrained('cars')
                  ->nullOnDelete();

            $table->string('car_snapshot_name')
                  ->comment('Car display name captured at booking time — survives car deletion');

            // renter = the buyer who booked
            $table->foreignId('renter_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // owner = the seller/business who listed the car.
            // Stored as a snapshot FK so history is preserved even if the
            // owner account is deleted (cascadeOnDelete kept for data integrity).
            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // --- Rental period ---
            $table->date('pickup_date');
            $table->date('return_date');
            $table->date('actual_return_date')
                  ->nullable()
                  ->comment('Filled in by owner when the car is physically returned');

            // --- Pricing snapshots (captured at booking time) ---
            $table->unsignedInteger('total_days');
            $table->unsignedInteger('price_per_day')
                  ->comment('NRs per day — snapshot of car rent_price_per_day');
            $table->unsignedInteger('deposit_amount')
                  ->nullable()
                  ->comment('NRs — snapshot of car rent_deposit');
            $table->unsignedInteger('total_price')
                  ->comment('price_per_day × total_days — computed and snapshotted at booking');

            // --- Lifecycle status ---
            // pending   → owner reviews the request
            // confirmed → owner approved; dates are locked; car is reserved
            // active    → owner marked the car as picked up by renter
            // completed → owner marked the car as returned
            // cancelled → cancelled by renter or owner (only from pending/confirmed)
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])
                  ->default('pending');

            // --- Renter contact snapshot ---
            $table->string('renter_name');
            $table->string('renter_phone');
            $table->string('renter_email');

            // --- Optional message from renter ---
            $table->text('notes')->nullable();

            // --- Cancellation meta ---
            $table->text('cancellation_reason')->nullable();
            $table->enum('cancelled_by', ['renter', 'owner'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};