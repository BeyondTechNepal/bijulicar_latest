<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_news', function (Blueprint $table) {
            $table->string('section_3_title')->nullable()->after('section_2_content');
            $table->text('section_3_content')->nullable()->after('section_3_title');
            $table->string('section_4_title')->nullable()->after('section_3_content');
            $table->text('section_4_content')->nullable()->after('section_4_title');
        });
    }

    public function down(): void
    {
        Schema::table('business_news', function (Blueprint $table) {
            $table->dropColumn([
                'section_3_title', 'section_3_content',
                'section_4_title', 'section_4_content',
            ]);
        });
    }
};