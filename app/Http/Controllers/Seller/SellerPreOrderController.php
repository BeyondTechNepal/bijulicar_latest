<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PreOrder;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerPreOrderController extends Controller
{
    private function context(): array
    {
        if (Auth::user()->hasRole('business')) {
            return ['prefix' => 'business', 'layout' => 'dashboard.business.layout'];
        }
        return ['prefix' => 'seller', 'layout' => 'dashboard.seller.layout'];
    }

    /** List all pre-orders on this seller's cars */
    public function index()
    {
        $ctx    = $this->context();
        $userId = Auth::id();

        $preOrders = PreOrder::whereHas('car', fn($q) => $q->where('seller_id', $userId))
            ->with('car', 'buyer')
            ->latest('placed_at')
            ->paginate(10);

        return view('dashboard.seller.preorders.index', array_merge(compact('preOrders'), $ctx));
    }

    /** Show a single pre-order detail */
    public function show(PreOrder $preOrder)
    {
        abort_if($preOrder->car->seller_id != Auth::id(), 403);
        $ctx = $this->context();
        $preOrder->load('car', 'buyer', 'order');
        return view('dashboard.seller.preorders.show', array_merge(compact('preOrder'), $ctx));
    }

    /** Show the confirm-deposit form */
    public function confirmDepositForm(PreOrder $preOrder)
    {
        abort_if($preOrder->car->seller_id != Auth::id(), 403);
        abort_if($preOrder->status !== 'pending_deposit', 422, 'Deposit already confirmed.');
        $ctx = $this->context();
        $preOrder->load('car', 'buyer');
        return view('dashboard.seller.preorders.confirm_deposit', array_merge(compact('preOrder'), $ctx));
    }

    /** Seller confirms deposit was received */
    public function confirmDeposit(Request $request, PreOrder $preOrder)
    {
        abort_if($preOrder->car->seller_id != Auth::id(), 403);
        abort_if($preOrder->status !== 'pending_deposit', 422, 'Deposit already confirmed.');

        $request->validate([
            'payment_method'  => ['required', 'in:cash,bank_transfer,emi,other'],
            'transaction_ref' => ['nullable', 'string', 'max:255'],
            'remarks'         => ['nullable', 'string', 'max:500'],
        ]);

        $preOrder->update([
            'status'          => 'deposit_paid',
            'payment_method'  => $request->payment_method,
            'transaction_ref' => $request->transaction_ref,
        ]);
        app(NotificationService::class)->preOrderDepositConfirmed($preOrder);

        $ctx = $this->context();
        return redirect()
            ->route($ctx['prefix'] . '.preorders.show', $preOrder)
            ->with('success', 'Deposit confirmed. When the car arrives, convert this to a full order.');
    }

    /**
     * Convert a pre-order to a full Order when car arrives.
     * This creates an Order in 'confirmed' state and marks the car as available.
     */
    public function convert(PreOrder $preOrder)
    {
        abort_if($preOrder->car->seller_id != Auth::id(), 403);
        abort_if($preOrder->status !== 'deposit_paid', 422, 'Can only convert pre-orders with confirmed deposits.');

        $car = $preOrder->car;

        // Create the full order (already confirmed since deposit was paid)
        $order = Order::create([
            'buyer_id'    => $preOrder->buyer_id,
            'car_id'      => $car->id,
            'status'      => 'confirmed',
            'total_price' => $car->price,
            'buyer_name'  => $preOrder->buyer_name,
            'buyer_phone' => $preOrder->buyer_phone,
            'buyer_email' => $preOrder->buyer_email,
            'notes'       => 'Converted from pre-order #' . $preOrder->id . '. Deposit of ' . $preOrder->formattedDeposit() . ' already paid.',
        ]);

        // Mark the pre-order as converted, link to the new order
        $preOrder->update([
            'status'   => 'converted',
            'order_id' => $order->id,
        ]);
        app(NotificationService::class)->preOrderConverted($preOrder);

        // Car is now available (arrived), turn off pre-order mode
        $car->update([
            'status'      => 'available',
            'is_preorder' => false,
        ]);

        $ctx = $this->context();
        return redirect()
            ->route($ctx['prefix'] . '.orders.show', $order)
            ->with('success', 'Pre-order converted to a full order. The buyer has been moved to the confirmed orders queue.');
    }

    /** Seller cancels a pre-order (and should refund deposit) */
    public function cancel(PreOrder $preOrder)
    {
        abort_if($preOrder->car->seller_id != Auth::id(), 403);
        abort_if(!$preOrder->isCancellable(), 422, 'This pre-order can no longer be cancelled.');

        $preOrder->update(['status' => 'cancelled']);
        app(NotificationService::class)->preOrderCancelledBySeller($preOrder);

        $ctx = $this->context();
        return redirect()
            ->route($ctx['prefix'] . '.preorders.index')
            ->with('success', 'Pre-order cancelled. Remember to refund the buyer\'s deposit.');
    }
}