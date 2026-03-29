<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BuyerPurchaseController extends Controller
{
    /**
     * Show all completed purchases for the logged-in buyer.
     * Purchases are created by the seller — we just display them here.
     */
    public function index()
    {
        $purchases = Auth::user()
            ->purchases()
            ->with('order.car')
            ->latest('purchased_at')
            ->paginate(10);

        return view('dashboard.buyer.purchases.index', compact('purchases'));
    }
}