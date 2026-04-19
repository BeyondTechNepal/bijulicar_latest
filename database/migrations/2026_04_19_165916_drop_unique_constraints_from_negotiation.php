<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MySQL uses the unique index on buyer_id to back its foreign key, so we
     * must drop the FK first, then drop the unique index, then restore the FK
     * (which Laravel will back with a plain index instead).
     *
     * Active-duplicate prevention is handled in BuyerNegotiationController@store
     * via a whereIn('status', ['pending_seller','pending_buyer','accepted']) check.
     */
    public function up(): void
    {
        Schema::table('negotiations', function (Blueprint $table) {
            // 1. Drop the foreign key that piggybacks on the unique index
            $table->dropForeign('negotiations_buyer_id_foreign');

            // 2. Now drop the unique index safely
            $table->dropUnique('negotiations_buyer_id_car_id_unique');

            // 3. Restore the foreign key (Laravel adds a plain index for it)
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('negotiations', function (Blueprint $table) {
            $table->dropForeign('negotiations_buyer_id_foreign');
            $table->unique(['buyer_id', 'car_id']);
            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};