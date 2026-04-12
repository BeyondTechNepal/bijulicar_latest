<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add a FULLTEXT index covering the three columns users search against.
     *
     * Using raw DDL instead of Blueprint because Laravel's Schema builder does
     * not expose FULLTEXT index creation directly.
     *
     * The index covers brand + model + variant together so a query like
     * "Tesla Model 3 Long Range" hits all three columns in one MATCH() call.
     *
     * MySQL minimum token length (ft_min_word_len) defaults to 4 for MyISAM
     * and 3 for InnoDB (innodb_ft_min_token_size). Queries shorter than that
     * fall back to LIKE in MarketplaceController::applyFilters().
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE cars ADD FULLTEXT INDEX cars_fulltext_search (brand, model, variant)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE cars DROP INDEX cars_fulltext_search');
    }
};