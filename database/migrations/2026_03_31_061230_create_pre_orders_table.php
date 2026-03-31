<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();

            // Deposit payment details
            $table->unsignedBigInteger('deposit_amount');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'emi', 'other'])->default('cash');
            $table->string('transaction_ref')->nullable();
            $table->text('notes')->nullable();

            // Lifecycle: pending_deposit → deposit_paid → converted | cancelled | refunded
            $table->enum('status', [
                'pending_deposit',  // placed but seller hasn't confirmed deposit yet
                'deposit_paid',     // seller confirmed deposit received
                'converted',        // car arrived, converted to a full Order
                'cancelled',        // cancelled before arrival
                'refunded',         // deposit was refunded
            ])->default('pending_deposit');

            // When this pre-order was converted to a regular order
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            // Buyer contact snapshot
            $table->string('buyer_name');
            $table->string('buyer_phone');
            $table->string('buyer_email');

            $table->timestamp('placed_at')->useCurrent();
            $table->timestamps();

            // One pre-order per buyer per car
            $table->unique(['buyer_id', 'car_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};