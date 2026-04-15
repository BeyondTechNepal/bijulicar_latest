<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerOrderController extends Controller
{
    private function authUser()
    {
        return Auth::guard('web')->user();
    }

    private function context(): array
    {
        $user = $this->authUser();

        if ($user && $user->hasRole('business')) {
            return ['prefix' => 'business', 'layout' => 'dashboard.business.layout'];
        }
        return ['prefix' => 'seller', 'layout' => 'dashboard.seller.layout'];
    }

    public function index()
    {
        $ctx    = $this->context();
        $userId = Auth::guard('web')->id();

        // Query by seller_id directly — works even when car has been deleted
        // and car_id is null. Previously used whereHas('car', ...) which
        // excluded orders whose car was soft-deleted.
        $orders = Order::where('seller_id', $userId)
            ->with(['car' => fn($q) => $q->withTrashed(), 'buyer'])
            ->latest('ordered_at')
            ->paginate(10);

        return view('dashboard.seller.orders.index', array_merge(compact('orders'), $ctx));
    }

    public function show(Order $order)
    {
        abort_if($order->seller_id != Auth::guard('web')->id(), 403);

        $ctx = $this->context();
        $order->load(['car' => fn($q) => $q->withTrashed(), 'buyer', 'purchase']);

        return view('dashboard.seller.orders.show', array_merge(compact('order'), $ctx));
    }

    public function confirm(Order $order)
    {
        abort_if($order->seller_id != Auth::guard('web')->id(), 403);
        abort_if($order->status !== 'pending', 422, 'Only pending orders can be confirmed.');

        $order->update(['status' => 'confirmed']);
        app(NotificationService::class)->orderConfirmed($order);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.orders.show', $order->id)
            ->with('success', 'Order confirmed. Once you receive payment, mark it as completed.');
    }

    public function completeForm(Order $order)
    {
        abort_if($order->seller_id != Auth::guard('web')->id(), 403);
        abort_if($order->status !== 'confirmed', 422, 'Only confirmed orders can be marked as completed.');
        abort_if($order->purchase()->exists(), 422, 'This order is already completed.');

        $ctx = $this->context();
        $order->load(['car' => fn($q) => $q->withTrashed(), 'buyer']);

        return view('dashboard.seller.orders.complete', array_merge(compact('order'), $ctx));
    }

    public function complete(Request $request, Order $order)
    {
        abort_if($order->seller_id != Auth::guard('web')->id(), 403);
        abort_if($order->status !== 'confirmed', 422, 'Only confirmed orders can be marked as completed.');

        $request->validate([
            'payment_method'  => ['required', 'in:cash,bank_transfer,emi,other'],
            'amount_paid'     => ['required', 'integer', 'min:1'],
            'transaction_ref' => ['nullable', 'string', 'max:255'],
            'remarks'         => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $order) {
            Purchase::create([
                'order_id'        => $order->id,
                'buyer_id'        => $order->buyer_id,
                'payment_method'  => $request->payment_method,
                'payment_status'  => 'paid',
                'amount_paid'     => $request->amount_paid,
                'transaction_ref' => $request->transaction_ref,
                'remarks'         => $request->remarks,
            ]);

            $order->update(['status' => 'completed']);

            // Only decrement stock if the car still exists (not deleted)
            if ($order->car_id) {
                $car = $order->car()->withTrashed()->lockForUpdate()->first();
                if ($car && ! $car->trashed()) {
                    $car->decrementStock();
                }
            }

            $order->load(['car' => fn($q) => $q->withTrashed(), 'buyer']);
            app(NotificationService::class)->orderCompleted($order);
        });

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.orders.show', $order->id)
            ->with('success', 'Order marked as completed. Payment recorded successfully.');
    }

    public function cancel(Order $order)
    {
        abort_if($order->seller_id != Auth::guard('web')->id(), 403);
        abort_if(!$order->isCancellable(), 422, 'This order can no longer be cancelled.');

        $order->update(['status' => 'cancelled']);
        $order->load(['car' => fn($q) => $q->withTrashed(), 'buyer']);
        app(NotificationService::class)->orderCancelledBySeller($order);

        // Only update car status if the car record still exists and isn't deleted
        if ($order->car_id && $order->car && ! $order->car->trashed()) {
            if ($order->car->status === 'reserved') {
                $order->car->update(['status' => 'available']);
            }
        }

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.orders.index')
            ->with('success', 'Order cancelled. The listing is available again.');
    }
}