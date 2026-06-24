<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experience_comments', function (Blueprint $table) {
            $table->id();

            // The experience this comment belongs to.
            // Deleting an experience wipes all its comments.
            $table->foreignId('car_experience_id')
                  ->constrained('car_experiences')
                  ->cascadeOnDelete();

            // The user who wrote this comment.
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Null = top-level comment.
            // Set = reply to a top-level comment (max 2 levels enforced in controller).
            // nullOnDelete so replies aren't orphaned if parent is deleted —
            // they just become detached and get cleaned up at query level.
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('experience_comments')
                  ->nullOnDelete();

            $table->text('body');

            // Flagged true when the author edits their comment — shown as "edited" in UI.
            $table->boolean('is_edited')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_comments');
    }
};