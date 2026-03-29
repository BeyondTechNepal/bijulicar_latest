<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    /** * Get the authenticated user for the web guard explicitly.
     */
    private function authUser()
    {
        return Auth::guard('web')->user();
    }

    /** Detect which role is using this controller and return the correct route prefix and layout. */
    private function context(): array
    {
        $user = $this->authUser();
        
        if ($user && $user->hasRole('business')) {
            return ['prefix' => 'business', 'layout' => 'dashboard.business.layout'];
        }
        return ['prefix' => 'seller', 'layout' => 'dashboard.seller.layout'];
    }

    /**
     * Show all orders placed on this seller's cars.
     */
    public function index()
    {
        $ctx = $this->context();
        $userId = Auth::guard('web')->id();

        $orders = Order::whereHas('car', function ($q) use ($userId) {
                $q->where('seller_id', $userId);
            })
            ->with('car', 'buyer')
            ->latest('ordered_at')
            ->paginate(10);

        return view('dashboard.seller.orders.index', array_merge(compact('orders'), $ctx));
    }

    /**
     * Show a single order in detail.
     */
    public function show(Order $order)
    {
        // Use != and explicit guard ID to prevent live-server 403s
        abort_if($order->car->seller_id != Auth::guard('web')->id(), 403);

        $ctx = $this->context();
        $order->load('car', 'buyer', 'purchase');

        return view('dashboard.seller.orders.show', array_merge(compact('order'), $ctx));
    }

    /**
     * Confirm a pending order.
     */
    public function confirm(Order $order)
    {
        abort_if($order->car->seller_id != Auth::guard('web')->id(), 403);
        abort_if($order->status !== 'pending', 422, 'Only pending orders can be confirmed.');

        $order->update(['status' => 'confirmed']);

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.orders.show', $order->id)
            ->with('success', 'Order confirmed. Once you receive payment, mark it as completed.');
    }

    /**
     * Show the completion form where seller records payment details.
     */
    public function completeForm(Order $order)
    {
        abort_if($order->car->seller_id != Auth::guard('web')->id(), 403);
        abort_if($order->status !== 'confirmed', 422, 'Only confirmed orders can be marked as completed.');
        abort_if($order->purchase()->exists(), 422, 'This order is already completed.');

        $ctx = $this->context();
        $order->load('car', 'buyer');

        return view('dashboard.seller.orders.complete', array_merge(compact('order'), $ctx));
    }

    /**
     * Save payment details and mark the order as completed.
     */
    public function complete(Request $request, Order $order)
    {
        abort_if($order->car->seller_id != Auth::guard('web')->id(), 403);
        abort_if($order->status !== 'confirmed', 422, 'Only confirmed orders can be marked as completed.');
        
        $request->validate([
            'payment_method'  => ['required', 'in:cash,bank_transfer,emi,other'],
            'amount_paid'     => ['required', 'integer', 'min:1'],
            'transaction_ref' => ['nullable', 'string', 'max:255'],
            'remarks'         => ['nullable', 'string', 'max:500'],
        ]);

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
        $order->car->decrementStock();

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.orders.show', $order->id)
            ->with('success', 'Order marked as completed. Payment recorded successfully.');
    }

    /**
     * Cancel an order (seller side).
     */
    public function cancel(Order $order)
    {
        abort_if($order->car->seller_id != Auth::guard('web')->id(), 403);
        abort_if(!$order->isCancellable(), 422, 'This order can no longer be cancelled.');

        $order->update(['status' => 'cancelled']);

        if ($order->car->status === 'reserved') {
            $order->car->update(['status' => 'available']);
        }

        $prefix = $this->context()['prefix'];

        return redirect()
            ->route($prefix . '.orders.index')
            ->with('success', 'Order cancelled. The listing is available again.');
    }
}