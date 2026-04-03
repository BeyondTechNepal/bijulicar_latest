<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Pricing rules table ────────────────────────────────────────
        // One row per (placement × priority) combination.
        // Admin edits these from the UI — no code changes needed to adjust pricing.
        Schema::create('ad_pricing_rules', function (Blueprint $table) {
            $table->id();

            $table->enum('placement', [
                'home',
                'marketplace',
                'news_sidebar',
                'news_detail_sidebar',
                'business_banner',
                'car_detail_horizontal',
                'business_profile',
            ]);

            // Mirrors Advertisement::PRIORITIES  (0=Standard, 1=Featured, 2=Premium)
            $table->unsignedTinyInteger('priority')->default(0);

            // Price charged per calendar day of the ad run
            $table->decimal('price_per_day', 10, 2);

            // Minimum booking length (prevents 1-day spam bookings)
            $table->unsignedSmallInteger('min_days')->default(7);

            // Admin can deactivate a tier without deleting it
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Only one price per placement+priority slot
            $table->unique(['placement', 'priority']);
        });

        // ── 2. Add review + payment columns to advertisements ─────────────
        Schema::table('advertisements', function (Blueprint $table) {

            // --- Review flow ---
            $table->enum('status', [
                'pending_review',
                'approved',       // price set, awaiting payment
                'rejected',
                'published',      // payment confirmed, ad is live
            ])->default('pending_review')->after('is_active');

            $table->text('rejection_reason')->nullable()->after('status');

            // Snapshot the price at approval time (rule may change later)
            $table->decimal('charged_amount', 10, 2)->nullable()->after('rejection_reason');

            // Which admin reviewed this, and when
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete()
                ->after('charged_amount');

            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');

            // --- Payment confirmation ---
            $table->decimal('amount_paid', 10, 2)->nullable()->after('reviewed_at');

            $table->enum('payment_method', ['cash', 'bank', 'esewa', 'other'])
                ->nullable()
                ->after('amount_paid');

            $table->text('payment_note')->nullable()->after('payment_method');

            $table->timestamp('paid_at')->nullable()->after('payment_note');
        });

        // ── 3. Fix is_active default — new ads must NOT go live on submit ─
        DB::statement('ALTER TABLE advertisements MODIFY COLUMN is_active TINYINT(1) NOT NULL DEFAULT 0');

        // ── 4. Seed default pricing so admin has something to start with ──
        // Prices are in NPR. Admin can change from the UI immediately.
        $placements = [
            'home', 'marketplace', 'news_sidebar',
            'news_detail_sidebar', 'business_banner',
            'car_detail_horizontal', 'business_profile',
        ];

        $tiers = [
            0 => ['price_per_day' => 200,  'min_days' => 7],   // Standard
            1 => ['price_per_day' => 500,  'min_days' => 7],   // Featured
            2 => ['price_per_day' => 1000, 'min_days' => 7],   // Premium
        ];

        $now = now();
        $rows = [];
        foreach ($placements as $placement) {
            foreach ($tiers as $priority => $config) {
                $rows[] = [
                    'placement'    => $placement,
                    'priority'     => $priority,
                    'price_per_day' => $config['price_per_day'],
                    'min_days'     => $config['min_days'],
                    'is_active'    => true,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }
        DB::table('ad_pricing_rules')->insert($rows);
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'status', 'rejection_reason', 'charged_amount',
                'reviewed_by', 'reviewed_at',
                'amount_paid', 'payment_method', 'payment_note', 'paid_at',
            ]);
        });

        Schema::dropIfExists('ad_pricing_rules');
    }
};