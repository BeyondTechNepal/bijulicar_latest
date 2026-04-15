<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class BusinessAnalyticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Listing stats — withTrashed() so deleted cars are counted in history
        $listings = Auth::user()->listedCars()->withTrashed();

        $totalListings    = $listings->clone()->count();
        $activeListings   = $listings->clone()->where('status', 'available')->whereNull('deleted_at')->count();
        $soldListings     = $listings->clone()->where('status', 'sold')->count();
        $inactiveListings = $listings->clone()->where('status', 'inactive')->whereNull('deleted_at')->count();

        // Order stats — query by seller_id directly so deleted-car orders are included
        $orders = Order::where('seller_id', $userId);

        $totalOrders     = $orders->clone()->count();
        $pendingOrders   = $orders->clone()->where('status', 'pending')->count();
        $confirmedOrders = $orders->clone()->where('status', 'confirmed')->count();
        $completedOrders = $orders->clone()->where('status', 'completed')->count();
        $cancelledOrders = $orders->clone()->where('status', 'cancelled')->count();

        // Revenue stats — via seller_id on orders; no car join needed, so revenue
        // is preserved even after a car listing is deleted.
        $totalRevenue = Purchase::whereHas('order', fn($q) => $q->where('seller_id', $userId))
            ->where('payment_status', 'paid')
            ->sum('amount_paid');

        // Drivetrain breakdown — only non-deleted listings (historical deleted ones
        // can't reliably be counted by drivetrain since car data may be gone)
        $drivetrainBreakdown = Auth::user()
            ->listedCars()  // excludes soft-deleted (correct for active fleet view)
            ->selectRaw('drivetrain, COUNT(*) as total')
            ->groupBy('drivetrain')
            ->pluck('total', 'drivetrain')
            ->toArray();

        // Recent orders — include orders where car was deleted
        $recentOrders = Order::where('seller_id', $userId)
            ->with(['car' => fn($q) => $q->withTrashed(), 'buyer'])
            ->latest('ordered_at')
            ->limit(5)
            ->get();

        // Pre-order stats
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

        return view('dashboard.business.analytics', compact(
            'totalListings',
            'activeListings',
            'soldListings',
            'inactiveListings',
            'totalOrders',
            'pendingOrders',
            'confirmedOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'drivetrainBreakdown',
            'recentOrders',
            'totalPreOrders',
            'pendingDepositPreOrders',
            'depositPaidPreOrders',
            'convertedPreOrders',
            'cancelledPreOrders',
            'recentPreOrders',
        ));
    }
}