<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class BusinessAnalyticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Listing stats 
        $listings = Auth::user()->listedCars();

        $totalListings     = $listings->count();
        $activeListings    = $listings->clone()->where('status', 'available')->count();
        $soldListings      = $listings->clone()->where('status', 'sold')->count();
        $inactiveListings  = $listings->clone()->where('status', 'inactive')->count();

        // Order stats
        $orders = Order::whereHas('car', fn($q) => $q->where('seller_id', $userId));

        $totalOrders     = $orders->clone()->count();
        $pendingOrders   = $orders->clone()->where('status', 'pending')->count();
        $confirmedOrders = $orders->clone()->where('status', 'confirmed')->count();
        $completedOrders = $orders->clone()->where('status', 'completed')->count();
        $cancelledOrders = $orders->clone()->where('status', 'cancelled')->count();

        //  Revenue stats 
        $totalRevenue = Purchase::whereHas('order.car', fn($q) => $q->where('seller_id', $userId))
            ->where('payment_status', 'paid')
            ->sum('amount_paid');

        // Drivetrain breakdown of own listings 
        $drivetrainBreakdown = Auth::user()
            ->listedCars()
            ->selectRaw('drivetrain, COUNT(*) as total')
            ->groupBy('drivetrain')
            ->pluck('total', 'drivetrain')
            ->toArray();

        //  Recent orders (last 5) 
        $recentOrders = Order::whereHas('car', fn($q) => $q->where('seller_id', $userId))
            ->with('car', 'buyer')
            ->latest('ordered_at')
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
        ));
    }
}