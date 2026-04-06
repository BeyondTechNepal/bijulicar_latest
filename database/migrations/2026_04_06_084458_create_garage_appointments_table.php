<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garage_appointments', function (Blueprint $table) {
            $table->id();

            // The garage owner this appointment belongs to
            $table->foreignId('garage_user_id')->constrained('users')->cascadeOnDelete();

            // The customer (any role) who booked
            $table->foreignId('customer_user_id')->constrained('users')->cascadeOnDelete();

            // Bay number at the garage
            $table->unsignedTinyInteger('bay_number')->nullable();

            // Appointment status flow: pending → approved → rejected / completed
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');

            // What the customer needs done
            $table->string('service_description');

            // Customer preferred appointment datetime
            $table->dateTime('requested_at');

            // Estimated time the garage will finish the job
            $table->dateTime('estimated_finish_at')->nullable();

            // Reason when rejected
            $table->text('rejection_reason')->nullable();

            // Optional note from garage to customer
            $table->text('garage_note')->nullable();

            $table->timestamps();

            $table->index('garage_user_id');
            $table->index('customer_user_id');
            $table->index('status');
        });

        // Add total bays to new_locations for garages
        // (total_slots and accepts_walkins already added by the EV migration above,
        //  so we only need to add these if running garage migration standalone)
        if (!Schema::hasColumn('new_locations', 'total_slots')) {
            Schema::table('new_locations', function (Blueprint $table) {
                $table->unsignedTinyInteger('total_slots')->default(0)->after('is_active');
                $table->boolean('accepts_walkins')->default(true)->after('total_slots');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_appointments');
    }
};