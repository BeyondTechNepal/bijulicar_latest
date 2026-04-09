<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Short machine-readable key — used for icons/colours in the view
            // e.g. ad_approved | ad_rejected | ad_published
            //      slot_approved | slot_rejected
            //      appointment_approved | appointment_rejected
            //      account_approved | account_rejected
            $table->string('type');

            $table->string('title');
            $table->text('body')->nullable();

            // Optional deep-link so the user can jump straight to the relevant page
            $table->string('url')->nullable();

            // NULL = unread, timestamp = when the user read it
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'read_at']);  // fast unread count query
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};