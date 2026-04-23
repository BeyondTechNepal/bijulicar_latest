<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use App\Models\PreOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerPreOrderController extends Controller
{
    /** List all pre-orders for this buyer */
    public function index()
    {
        $preOrders = Auth::user()
            ->preOrders()
            ->with(['car' => fn($q) => $q->withTrashed()])
            ->latest('placed_at')
            ->paginate(10);

        return view('dashboard.buyer.preorders.index', compact('preOrders'));
    }

    /** Show the pre-order form for a specific car */
    public function create(Car $car)
    {
        abort_unless($car->isPreorderable(), 404);
        abort_if($car->seller_id === Auth::id(), 422, 'You cannot pre-order your own listing.');

        $existing = PreOrder::where('buyer_id', Auth::id())
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending_deposit', 'deposit_paid'])
            ->first();

        return view('dashboard.buyer.preorders.create', compact('car', 'existing'));
    }

    /** Place the pre-order */
    public function store(Request $request)
    {
        $request->validate([
            'car_id'      => ['required', 'exists:cars,id'],
            'buyer_name'  => ['required', 'string', 'max:100'],
            'buyer_phone' => ['required', 'string', 'max:20'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $car     = Car::findOrFail($request->car_id);
        $buyerId = Auth::id();

        abort_unless($car->isPreorderable(), 422, 'This car is not available for pre-order.');
        abort_if($car->seller_id == $buyerId, 422, 'You cannot pre-order your own listing.');

        $alreadyExists = PreOrder::where('buyer_id', $buyerId)
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending_deposit', 'deposit_paid'])
            ->exists();

        abort_if($alreadyExists, 422, 'You already have an active pre-order for this car.');

        $preOrder = PreOrder::create([
            'buyer_id'    => $buyerId,
            'car_id'      => $car->id,
            'deposit_amount' => $car->preorder_deposit,
            'buyer_name'  => $request->buyer_name,
            'buyer_phone' => $request->buyer_phone,
            'buyer_email' => $request->buyer_email,
            'notes'       => $request->notes,
            'status'      => 'pending_deposit',
        ]);

        return redirect()
            ->route('buyer.preorders.show', $preOrder)
            ->with('success', 'Pre-order placed! The seller will contact you to arrange the deposit.');
    }

    /** Show a single pre-order */
    public function show(PreOrder $preOrder)
    {
        abort_if($preOrder->buyer_id != Auth::id(), 403);
        $preOrder->load('car', 'order');
        return view('dashboard.buyer.preorders.show', compact('preOrder'));
    }

    /** Buyer cancels their pre-order */
    public function cancel(PreOrder $preOrder)
    {
        abort_if($preOrder->buyer_id != Auth::id(), 403);
        abort_if(!$preOrder->isCancellable(), 422, 'This pre-order can no longer be cancelled.');

        $preOrder->update(['status' => 'cancelled']);

        // Same car-reset logic as SellerPreOrderController@cancel: if this was
        // the last active pre-order, clear is_preorder so the car isn't stranded.
        $car = $preOrder->car;
        $hasOtherActivePreOrders = PreOrder::where('car_id', $car->id)
            ->whereIn('status', ['pending_deposit', 'deposit_paid'])
            ->exists();

        if (!$hasOtherActivePreOrders && $car->is_preorder && $car->status === 'upcoming') {
            $car->update([
                'status'      => 'upcoming',
                'is_preorder' => false,
            ]);
        }

        return redirect()
            ->route('buyer.preorders.index')
            ->with('success', 'Pre-order cancelled. Contact the seller regarding your deposit refund.');
    }
}