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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            
            // --- 1. NAVIGATION & BREADCRUMBS ---
            // $table->string('parent_hub')->default('Intelligence Hub');
            // $table->string('topic'); // e.g., Advanced Propulsion
            
            // --- 2. HEADER (H1) ---
            $table->string('title'); // Hydrogen Combustion:
            $table->string('title_highlight')->nullable(); // The Silent Rival
            $table->string('title_suffix')->nullable(); // to EV Dominance
            $table->string('slug')->unique();

            // --- 3. AUTHOR BLOCK ---
            $table->string('author_initials', 2); // AT
            $table->string('author_name'); // Alex Thorne
            $table->string('author_role'); // Chief Technical Analyst
            
            // --- 4. METADATA ---
            // $table->string('complexity')->nullable(); // Level 4/5
            
            // --- 5. HERO FIGURE ---
            $table->string('hero_image'); 
            // $table->string('figure_label')->nullable(); // Fig 1.0
            $table->string('figure_caption')->nullable(); // Prototype H2-ICE...
            
            // --- 6. BODY CONTENT ---
            $table->text('lead_paragraph'); // The big italicized intro with green border
            
            // Section I
            $table->string('section_1_title')->nullable();
            $table->text('section_1_content')->nullable();
            
            // --- 7. TECHNICAL SPEC BOX (JSON) ---
            // Stores: [{"label": "Diesel", "value": "45 MJ/kg"}, ...]
            $table->json('tech_specs')->nullable(); 
            $table->text('tech_note')->nullable(); // The "Technical Note" italic text
            
            // Section II
            $table->string('section_2_title')->nullable();
            $table->text('section_2_content')->nullable();
            
            // --- 8. THE BIG QUOTE ---
            $table->text('quote_text')->nullable();
            $table->string('quote_author')->nullable(); // Dr. Helena Vane
            $table->string('quote_author_title')->nullable(); // Propulsion Lead
            
            // Section III
            $table->string('section_3_title')->nullable();
            $table->text('section_3_content')->nullable();
            
            // --- 9. STATUS ---
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
