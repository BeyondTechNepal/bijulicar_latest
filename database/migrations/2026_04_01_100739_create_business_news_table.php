<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('news_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('hero_image')->nullable();
            $table->string('figure_caption')->nullable();
            $table->text('lead_paragraph');
            $table->string('section_1_title')->nullable();
            $table->text('section_1_content')->nullable();
            $table->text('quote_text')->nullable();
            $table->string('quote_author')->nullable();
            $table->string('section_2_title')->nullable();
            $table->text('section_2_content')->nullable();
            $table->string('author_name');
            $table->string('author_role')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_news');
    }
};