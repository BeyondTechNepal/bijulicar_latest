<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\CarRental;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class BusinessAnalyticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ── Listing stats ──────────────────────────────────────────────
        $listings = Auth::user()->listedCars()->withTrashed();

        $totalListings    = $listings->clone()->count();
        $activeListings   = $listings->clone()->where('status', 'available')->whereNull('deleted_at')->count();
        $soldListings     = $listings->clone()->where('status', 'sold')->count();
        $inactiveListings = $listings->clone()->where('status', 'inactive')->whereNull('deleted_at')->count();

        // ── Sale order stats ───────────────────────────────────────────
        $orders = Order::where('seller_id', $userId);

        $totalOrders     = $orders->clone()->count();
        $pendingOrders   = $orders->clone()->where('status', 'pending')->count();
        $confirmedOrders = $orders->clone()->where('status', 'confirmed')->count();
        $completedOrders = $orders->clone()->where('status', 'completed')->count();
        $cancelledOrders = $orders->clone()->where('status', 'cancelled')->count();

        // ── Sale revenue ───────────────────────────────────────────────
        $totalRevenue = Purchase::whereHas('order', fn($q) => $q->where('seller_id', $userId))
            ->where('payment_status', 'paid')
            ->sum('amount_paid');

        // ── Drivetrain breakdown ───────────────────────────────────────
        $drivetrainBreakdown = Auth::user()
            ->listedCars()
            ->selectRaw('drivetrain, COUNT(*) as total')
            ->groupBy('drivetrain')
            ->pluck('total', 'drivetrain')
            ->toArray();

        // ── Recent sale orders ─────────────────────────────────────────
        $recentOrders = Order::where('seller_id', $userId)
            ->with(['car' => fn($q) => $q->withTrashed(), 'buyer'])
            ->latest('ordered_at')
            ->limit(5)
            ->get();

        // ── Pre-order stats ────────────────────────────────────────────
        $preOrders = PreOrder::whereHas('car', fn($q) => $q->withTrashed()->where('seller_id', $userId));

        $totalPreOrders          = (clone $preOrders)->count();
        $pendingDepositPreOrders = (clone $preOrders)->where('status', 'pending_deposit')->count();
        $depositPaidPreOrders    = (clone $preOrders)->where('status', 'deposit_paid')->count();
        $convertedPreOrders      = (clone $preOrders)->where('status', 'converted')->count();
        $cancelledPreOrders      = (clone $preOrders)->where('status', 'cancelled')->count();

        $recentPreOrders = (clone $preOrders)
            ->with(['car' => fn($q) => $q->withTrashed(), 'buyer'])
            ->latest()
            ->limit(5)
            ->get();

        // ── Rental stats ───────────────────────────────────────────────
        $rentals = CarRental::where('owner_id', $userId);

        $totalRentals     = (clone $rentals)->count();
        $pendingRentals   = (clone $rentals)->where('status', 'pending')->count();
        $confirmedRentals = (clone $rentals)->where('status', 'confirmed')->count();
        $activeRentals    = (clone $rentals)->where('status', 'active')->count();
        $completedRentals = (clone $rentals)->where('status', 'completed')->count();
        $cancelledRentals = (clone $rentals)->where('status', 'cancelled')->count();

        $totalRentalRevenue = (clone $rentals)
            ->where('status', 'completed')
            ->sum('total_price');

        $totalRentalDays = (clone $rentals)
            ->where('status', 'completed')
            ->sum('total_days');

        $recentRentals = (clone $rentals)
            ->with(['car' => fn($q) => $q->withTrashed(), 'renter'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.business.analytics', compact(
            'totalListings', 'activeListings', 'soldListings', 'inactiveListings',
            'totalOrders', 'pendingOrders', 'confirmedOrders', 'completedOrders',
            'cancelledOrders', 'totalRevenue', 'drivetrainBreakdown', 'recentOrders',
            'totalPreOrders', 'pendingDepositPreOrders', 'depositPaidPreOrders',
            'convertedPreOrders', 'cancelledPreOrders', 'recentPreOrders',
            'totalRentals', 'pendingRentals', 'confirmedRentals', 'activeRentals',
            'completedRentals', 'cancelledRentals', 'totalRentalRevenue',
            'totalRentalDays', 'recentRentals',
        ));
    }
}