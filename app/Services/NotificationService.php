<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\User;
use App\Models\UserNotification;

/**
 * Central service for creating in-app notifications.
 *
 * Call these right after (or alongside) the existing Mail::send() calls
 * or status update lines in each controller.
 */
class NotificationService
{
    // ── Advertisement ──────────────────────────────────────────────────

    public function adApproved(Advertisement $ad): void
    {
        $this->create(
            userId: $ad->user_id,
            type:   'ad_approved',
            title:  'Ad approved — "' . $ad->title . '"',
            body:   'Your advertisement has been approved by admin. Please complete the payment to publish it.',
            url:    route('business.advertisements.index'),
        );
    }

    public function adRejected(Advertisement $ad): void
    {
        $reason = $ad->rejection_reason
            ? 'Reason: ' . $ad->rejection_reason
            : 'Please review and resubmit your advertisement.';

        $this->create(
            userId: $ad->user_id,
            type:   'ad_rejected',
            title:  'Ad rejected — "' . $ad->title . '"',
            body:   $reason,
            url:    route('business.advertisements.index'),
        );
    }

    public function adPublished(Advertisement $ad): void
    {
        $this->create(
            userId: $ad->user_id,
            type:   'ad_published',
            title:  'Ad is now live — "' . $ad->title . '"',
            body:   'Your advertisement is published and visible to users.',
            url:    route('business.advertisements.index'),
        );
    }

    // ── EV Slot ────────────────────────────────────────────────────────

    public function slotApproved(EvStationSlot $slot, User $customer): void
    {
        $this->create(
            userId: $customer->id,
            type:   'slot_approved',
            title:  'EV slot confirmed — Port #' . $slot->slot_number,
            body:   'Your charging slot request has been confirmed. Head to the station.',
            url:    route('booking.mine'),
        );
    }

    public function slotRejected(EvStationSlot $slot, User $customer, string $reason = ''): void
    {
        $body = 'Your slot request was rejected by the station.';
        if ($reason) {
            $body .= ' Reason: ' . $reason;
        }

        $this->create(
            userId: $customer->id,
            type:   'slot_rejected',
            title:  'EV slot rejected — Port #' . $slot->slot_number,
            body:   $body,
            url:    route('booking.mine'),
        );
    }

    /**
     * Fired when a station manually marks a BOOKED slot as OCCUPIED
     * (i.e. the vehicle has physically arrived at the station).
     * Only notify if a customer (occupant) is actually linked to the slot.
     */
    public function slotOccupied(EvStationSlot $slot, User $customer): void
    {
        $this->create(
            userId: $customer->id,
            type:   'slot_occupied',
            title:  'EV slot now active — Port #' . $slot->slot_number,
            body:   'The station has marked your slot as in use. Charging session started.',
            url:    route('booking.mine'),
        );
    }

    // ── Garage Appointment ─────────────────────────────────────────────

    public function appointmentApproved(GarageAppointment $appointment): void
    {
        $garageName = $appointment->garage->name ?? 'the garage';
        $body = "Your appointment at {$garageName} has been confirmed.";

        if ($appointment->estimated_finish_at) {
            $body .= ' Estimated finish: ' . $appointment->estimated_finish_at->format('h:i A, d M');
        }
        if ($appointment->garage_note) {
            $body .= ' Note: ' . $appointment->garage_note;
        }

        $this->create(
            userId: $appointment->customer_user_id,
            type:   'appointment_approved',
            title:  'Appointment confirmed — ' . $garageName,
            body:   $body,
            url:    route('booking.mine'),
        );
    }

    public function appointmentRejected(GarageAppointment $appointment): void
    {
        $garageName = $appointment->garage->name ?? 'the garage';
        $body = "Your appointment at {$garageName} was not accepted.";

        if ($appointment->rejection_reason) {
            $body .= ' Reason: ' . $appointment->rejection_reason;
        }

        $this->create(
            userId: $appointment->customer_user_id,
            type:   'appointment_rejected',
            title:  'Appointment rejected — ' . $garageName,
            body:   $body,
            url:    route('booking.mine'),
        );
    }

    /**
     * Fired when garage marks the appointment as completed (job done).
     */
    public function appointmentCompleted(GarageAppointment $appointment): void
    {
        $garageName = $appointment->garage->name ?? 'the garage';

        $this->create(
            userId: $appointment->customer_user_id,
            type:   'appointment_completed',
            title:  'Service completed — ' . $garageName,
            body:   'Your vehicle service has been completed. Thank you for using BijuliCar.',
            url:    route('booking.mine'),
        );
    }

    // ── Order ──────────────────────────────────────────────────────────

    /**
     * Seller confirms a pending order → buyer notified.
     * Trigger in: SellerOrderController::confirm()
     */
    public function orderConfirmed(Order $order): void
    {
        $carName = $order->car->title ?? 'your car';

        $this->create(
            userId: $order->buyer_id,
            type:   'order_confirmed',
            title:  'Order confirmed — ' . $carName,
            body:   'The seller has confirmed your order. Please arrange payment to complete the purchase.',
            url:    route('buyer.orders.show', $order->id),
        );
    }

    /**
     * Seller records payment and marks order as completed → buyer notified.
     * Trigger in: SellerOrderController::complete()
     */
    public function orderCompleted(Order $order): void
    {
        $carName = $order->car->title ?? 'your car';

        $this->create(
            userId: $order->buyer_id,
            type:   'order_completed',
            title:  'Purchase complete — ' . $carName,
            body:   'Your order has been marked as completed. Congratulations on your new car!',
            url:    route('buyer.orders.show', $order->id),
        );
    }

    /**
     * Seller cancels an order → buyer notified.
     * Trigger in: SellerOrderController::cancel()
     */
    public function orderCancelledBySeller(Order $order): void
    {
        $carName = $order->car->title ?? 'your car';

        $this->create(
            userId: $order->buyer_id,
            type:   'order_cancelled',
            title:  'Order cancelled — ' . $carName,
            body:   'The seller has cancelled your order. The listing may be available again.',
            url:    route('buyer.orders.index'),
        );
    }

    // ── Pre-Order ──────────────────────────────────────────────────────

    /**
     * Seller confirms the deposit was received → buyer notified.
     * Trigger in: SellerPreOrderController::confirmDeposit()
     */
    public function preOrderDepositConfirmed(PreOrder $preOrder): void
    {
        $carName = $preOrder->car->title ?? 'your pre-ordered car';

        $this->create(
            userId: $preOrder->buyer_id,
            type:   'preorder_deposit_confirmed',
            title:  'Deposit confirmed — ' . $carName,
            body:   'Your deposit has been received and confirmed by the seller. We will notify you when the car arrives.',
            url:    route('buyer.preorders.show', $preOrder->id),
        );
    }

    /**
     * Seller converts pre-order to a full order (car arrived) → buyer notified.
     * Trigger in: SellerPreOrderController::convert()
     */
    public function preOrderConverted(PreOrder $preOrder): void
    {
        $carName = $preOrder->car->title ?? 'your pre-ordered car';

        $this->create(
            userId: $preOrder->buyer_id,
            type:   'preorder_converted',
            title:  'Your car has arrived — ' . $carName,
            body:   'Your pre-ordered car is now available. Your order has been moved to confirmed orders.',
            url:    route('buyer.orders.show', $preOrder->order_id),
        );
    }

    /**
     * Seller cancels a pre-order → buyer notified.
     * Trigger in: SellerPreOrderController::cancel()
     */
    public function preOrderCancelledBySeller(PreOrder $preOrder): void
    {
        $carName = $preOrder->car->title ?? 'your pre-ordered car';

        $this->create(
            userId: $preOrder->buyer_id,
            type:   'preorder_cancelled',
            title:  'Pre-order cancelled — ' . $carName,
            body:   'The seller has cancelled your pre-order. Please contact the seller regarding your deposit refund.',
            url:    route('buyer.preorders.index'),
        );
    }

    // ── Account Verification ───────────────────────────────────────────

    public function accountApproved(User $user): void
    {
        $this->create(
            userId: $user->id,
            type:   'account_approved',
            title:  'Account verified',
            body:   'Your account has been approved. You now have full access to your dashboard.',
            url:    route('dashboard'),
        );
    }

    public function accountRejected(User $user, string $reason = ''): void
    {
        $body = 'Your account verification was not approved.';
        if ($reason) {
            $body .= ' Reason: ' . $reason;
        }

        $this->create(
            userId: $user->id,
            type:   'account_rejected',
            title:  'Account verification rejected',
            body:   $body,
            url:    route('verification.pending'),
        );
    }

    // ── Internal ──────────────────────────────────────────────────────

    private function create(int $userId, string $type, string $title, string $body = '', ?string $url = null): void
    {
        UserNotification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body ?: null,
            'url'     => $url,
            'read_at' => null,
        ]);

        // Bust the cached unread count so the nav badge reflects the new notification.
        \Illuminate\Support\Facades\Cache::forget("user_unread_notifications_{$userId}");
    }
}