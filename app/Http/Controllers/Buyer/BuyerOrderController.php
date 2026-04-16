<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerOrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with(['car' => fn($q) => $q->withTrashed()])
            ->latest('ordered_at')
            ->paginate(10);

        return view('dashboard.buyer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->buyer_id != Auth::guard('web')->id(), 403);

        $order->load(['car' => fn($q) => $q->withTrashed(), 'purchase', 'preOrder']);
        return view('dashboard.buyer.orders.show', compact('order'));
    }

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
        $buyerId = Auth::guard('web')->id();

        abort_if(!$car->isAvailable(), 422, 'This car is no longer available.');
        abort_if(!$car->inStock(),     422, 'This car is currently out of stock.');
        abort_if($car->seller_id == $buyerId, 422, 'You cannot order your own listing.');

        $hasActiveOrder = Order::where('buyer_id', $buyerId)
            ->where('car_id', $car->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        abort_if($hasActiveOrder, 422, 'You already have an active order for this car.');

        $order = Order::create([
            'buyer_id'          => $buyerId,
            'seller_id'         => $car->seller_id,          // snapshot — survives car deletion
            'car_id'            => $car->id,
            'car_snapshot_name' => $car->displayName(),      // snapshot — survives car deletion
            'status'            => 'pending',
            'total_price'       => $car->price,
            'buyer_name'        => $request->buyer_name,
            'buyer_phone'       => $request->buyer_phone,
            'buyer_email'       => $request->buyer_email,
            'notes'             => $request->notes,
            'ordered_at'        => now(),
        ]);

        return redirect()
            ->route('buyer.orders.show', $order->id)
            ->with('success', 'Order placed! The seller will confirm shortly.');
    }

    public function cancel(Order $order)
    {
        abort_if($order->buyer_id != Auth::guard('web')->id(), 403);
        abort_if(!$order->isCancellable(), 422, 'This order can no longer be cancelled.');

        $order->update(['status' => 'cancelled']);

        if ($order->car && $order->car->status === 'reserved') {
            $order->car->update(['status' => 'available']);
        }

        return redirect()
            ->route('buyer.orders.index')
            ->with('success', 'Order cancelled. The listing is available again.');
    }
}