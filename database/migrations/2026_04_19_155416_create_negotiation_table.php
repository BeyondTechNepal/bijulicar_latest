<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Negotiations table.
     *
     * One negotiation per (buyer, car) pair — enforced by unique index.
     * A negotiation holds a thread of rounds (up to 3 back-and-forth offers).
     * When accepted, it stores the agreed price and the buyer can place an
     * order at that price instead of the listed price.
     *
     * Status lifecycle:
     *   pending_seller  → seller needs to respond (buyer just made an offer / countered)
     *   pending_buyer   → buyer needs to respond (seller countered)
     *   accepted        → both sides agreed on offered_price
     *   declined        → seller declined and closed the negotiation
     *   expired         → no response within 48 hours, auto-closed
     *   cancelled       → buyer withdrew the offer
     */
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();

            // The price currently on the table (changes each round)
            $table->unsignedBigInteger('offered_price');

            // The car's listed price at the time the negotiation was started
            $table->unsignedBigInteger('listed_price');

            // Who needs to act next
            $table->enum('status', [
                'pending_seller',   // waiting for seller to respond
                'pending_buyer',    // seller countered, waiting for buyer
                'accepted',         // deal agreed
                'declined',         // seller declined
                'expired',          // timed out
                'cancelled',        // buyer cancelled
            ])->default('pending_seller');

            // How many rounds have happened (max 3)
            $table->unsignedTinyInteger('rounds')->default(0);

            // Optional message from whoever made the last move
            $table->text('message')->nullable();

            // When the current round expires (48 hrs from last action)
            $table->timestamp('expires_at')->nullable();

            // One active negotiation per buyer per car
            $table->unique(['buyer_id', 'car_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};