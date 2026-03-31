<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('seller_verifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('full_name');
        $table->string('contact');
        $table->string('national_id_path');   // stored on private disk
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('seller_verifications');
}
};
