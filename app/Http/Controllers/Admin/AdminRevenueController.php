<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminRevenueController extends Controller
{
    public function index(Request $request)
    {
        // ── Period filter ──────────────────────────────────────────────
        $period = $request->get('period', '3months');

        $startDate = match ($period) {
            '1month'  => now()->subMonth()->startOfDay(),
            '3months' => now()->subMonths(3)->startOfDay(),
            '6months' => now()->subMonths(6)->startOfDay(),
            '1year'   => now()->subYear()->startOfDay(),
            '2years'  => now()->subYears(2)->startOfDay(),
            'all'     => Carbon::createFromDate(2000, 1, 1)->startOfDay(),
            default   => now()->subMonths(3)->startOfDay(),
        };

        $endDate = now()->endOfDay();

        // ── Base query: ads with confirmed payment in the period ───────
        $base = Advertisement::whereNotNull('paid_at')
            ->where('amount_paid', '>', 0)
            ->whereBetween('paid_at', [$startDate, $endDate]);

        // ── Summary figures ────────────────────────────────────────────
        $totalRevenue = (clone $base)->sum('amount_paid');
        $totalCount   = (clone $base)->count();

        // Breakdown by payment method
        $byMethod = (clone $base)
            ->selectRaw('payment_method, SUM(amount_paid) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // Breakdown by placement
        $byPlacement = (clone $base)
            ->selectRaw('placement, SUM(amount_paid) as total, COUNT(*) as count')
            ->groupBy('placement')
            ->orderByDesc('total')
            ->get();

        // ── Monthly chart data ─────────────────────────────────────────
        // FIX: The original code called now()->startOfMonth()->subMonths($i) twice
        // per iteration and chained ->endOfMonth() on the second call. Carbon mutates
        // in place, so $mStart was being silently changed to end-of-month as well,
        // making both bounds identical and causing the whereBetween to return 0 rows
        // for every month. Fix: build $mStart once with Carbon::now() and derive
        // $mEnd from a separate clone so neither object mutates the other.
        $chartMonths = match ($period) {
            '1month'  => 1,
            '3months' => 3,
            '6months' => 6,
            '1year'   => 12,
            '2years'  => 24,
            'all'     => 12,
            default   => 3,
        };

        $monthlyData = [];
        for ($i = $chartMonths - 1; $i >= 0; $i--) {
            // Use Carbon::now() independently for each bound so mutations don't bleed
            $mStart = Carbon::now()->startOfMonth()->subMonths($i)->startOfDay();
            $mEnd   = Carbon::now()->startOfMonth()->subMonths($i)->endOfMonth()->endOfDay();

            $mTotal = Advertisement::whereNotNull('paid_at')
                ->where('amount_paid', '>', 0)
                ->whereBetween('paid_at', [$mStart, $mEnd])
                ->sum('amount_paid');

            $monthlyData[] = [
                'label' => $mStart->format('M Y'),
                'total' => (float) $mTotal,
            ];
        }

        // ── Recent transactions ────────────────────────────────────────
        $transactions = (clone $base)
            ->with('owner')
            ->orderByDesc('paid_at')
            ->limit(15)
            ->get();

        return view('admin.revenue.index', compact(
            'period',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalCount',
            'byMethod',
            'byPlacement',
            'monthlyData',
            'transactions',
        ));
    }
}