<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_article', function (Blueprint $table) {
            $table->id();

            // Header / Meta
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable(); // e.g., "Beyond the Horizon: Testing the 2026 Toyota BZ-X..."
            $table->string('author_name')->nullable();
            $table->string('author_role')->nullable();
            $table->string('author_image')->nullable();
            $table->string('read_time')->nullable(); // e.g., "8 Min Read" ->remove
            $table->string('badge_text')->nullable(); // e.g., "In-Depth Review"
            $table->date('published_at')->nullable();

            // Hero Image
            $table->string('hero_image')->nullable();
            $table->string('hero_caption')->nullable();

            // Article content
            $table->longText('content_html')->nullable(); // store the article body (including paragraphs, blockquotes, headings, etc.)

            // Vehicle Specs (store as JSON)
            $table->json('vehicle_specs')->nullable();
            /*
                Example:
                [
                    {"key": "Powertrain", "value": "Dual-Motor AWD"},
                    {"key": "Output", "value": "320 HP / 450 Nm"},
                    {"key": "Charging", "value": "150kW DC Fast"},
                    {"key": "Clearance", "value": "215 mm"}
                ]
            */

            // Images inside content
            $table->json('content_images')->nullable();
            /*
                Example: ["url1","url2"]
            */

            // Related articles / "Keep Reading" section
            $table->json('related_articles')->nullable();
            /*
                Example:
                [
                    {"title": "The Best EV Chargers to Install in 2026", "category": "Tech Guide", "image": "url"},
                    {"title": "Luxury Sedans: Are they dying out?", "category": "Editorial", "image": "url"}
                ]
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_article');
    }
};
