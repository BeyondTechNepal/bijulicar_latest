<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garage_bays', function (Blueprint $table) {
            $table->id();

            // The garage owner
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Bay number within the garage (e.g. 1, 2, 3)
            $table->unsignedTinyInteger('bay_number');

            // available = free, occupied = someone is in it (walk-in or appointment)
            $table->enum('status', ['available', 'occupied'])->default('available');

            // Walk-in customer name (free text — they may not be a registered user)
            $table->string('walkin_customer_name')->nullable();

            // Short note about the job being done
            $table->string('service_note')->nullable();

            // Estimated finish time
            $table->dateTime('estimated_finish_at')->nullable();

            // If bay was occupied via an appointment link it here
            $table->foreignId('appointment_id')->nullable()->constrained('garage_appointments')->nullOnDelete();

            $table->timestamps();

            $table->unique(['user_id', 'bay_number']);
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_bays');
    }
};