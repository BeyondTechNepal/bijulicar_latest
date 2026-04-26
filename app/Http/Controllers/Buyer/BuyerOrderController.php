<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Negotiation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $buyerId = Auth::guard('web')->id();

        $result = DB::transaction(function () use ($request, $buyerId) {
            // Lock the car row for the duration of this transaction.
            // Any concurrent request hitting this same car will block here
            // until we commit or roll back, preventing double-orders.
            $car = Car::lockForUpdate()->findOrFail($request->car_id);

            abort_if(!$car->isAvailable(), 422, 'This car is no longer available.');
            abort_if(!$car->inStock(),     422, 'This car is currently out of stock.');
            abort_if($car->seller_id == $buyerId, 422, 'You cannot order your own listing.');
            abort_if(!$car->isSaleable(),  422, 'This car is not listed for sale.');

            // Block purchase if the car is currently out on an active rental
            abort_if(
                $car->hasActiveRental(),
                422,
                'This car is currently out on rental and cannot be ordered right now. Please try again once the rental period ends.'
            );

            $hasActiveOrder = Order::where('buyer_id', $buyerId)
                ->where('car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            abort_if($hasActiveOrder, 422, 'You already have an active order for this car.');

            // Check for an accepted negotiation — use that price instead of listed price
            $acceptedNegotiation = Negotiation::where('buyer_id', $buyerId)
                ->where('car_id', $car->id)
                ->where('status', 'accepted')
                ->first();

            $finalPrice = $acceptedNegotiation
                ? $acceptedNegotiation->offered_price
                : $car->price;

            $order = Order::create([
                'buyer_id'          => $buyerId,
                'seller_id'         => $car->seller_id,
                'car_id'            => $car->id,
                'car_snapshot_name' => $car->displayName(),
                'status'            => 'pending',
                'total_price'       => $finalPrice,
                'buyer_name'        => $request->buyer_name,
                'buyer_phone'       => $request->buyer_phone,
                'buyer_email'       => $request->buyer_email,
                'notes'             => $request->notes,
                'ordered_at'        => now(),
            ]);

            // Mark the negotiation as converted to an order
            if ($acceptedNegotiation) {
                $acceptedNegotiation->update(['status' => 'ordered']);
            }

            return ['order' => $order, 'negotiation' => $acceptedNegotiation, 'finalPrice' => $finalPrice];
        });

        $order               = $result['order'];
        $acceptedNegotiation = $result['negotiation'];
        $finalPrice          = $result['finalPrice'];

        $suffix = $acceptedNegotiation
            ? ' Negotiated price of NRs ' . number_format($finalPrice) . ' applied.'
            : '';

        return redirect()
            ->route('buyer.orders.show', $order->id)
            ->with('success', 'Order placed! The seller will confirm shortly.' . $suffix);
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