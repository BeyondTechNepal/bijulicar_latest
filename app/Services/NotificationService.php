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
        // This fires immediately when admin confirms payment.
        // If starts_at is in the future, the message reflects the scheduled date.
        $startsAt = $ad->starts_at;
        $today    = now()->toDateString();

        $isScheduled = $startsAt && $startsAt->toDateString() > $today;

        $body = $isScheduled
            ? "Payment confirmed. Your ad is scheduled to go live on {$startsAt->format('M d, Y')}."
            : 'Your advertisement is published and visible to users.';

        $this->create(
            userId: $ad->user_id,
            type:   'ad_published',
            title:  $isScheduled
                        ? 'Ad scheduled — "' . $ad->title . '"'
                        : 'Ad is now live — "' . $ad->title . '"',
            body:   $body,
            url:    route('business.advertisements.index'),
        );
    }

    /**
     * Fired by ads:sync-status when a future-dated ad's start date arrives.
     */
    public function adWentLive(Advertisement $ad): void
    {
        $this->create(
            userId: $ad->user_id,
            type:   'ad_went_live',
            title:  'Your ad is now live — "' . $ad->title . '"',
            body:   'Your scheduled advertisement has started and is now visible to all visitors on Bijulicar.',
            url:    route('business.advertisements.index'),
        );
    }

    /**
     * Fired by ads:sync-status when an ad's end_date has passed.
     */
    public function adExpired(Advertisement $ad): void
    {
        $this->create(
            userId: $ad->user_id,
            type:   'ad_expired',
            title:  'Ad run ended — "' . $ad->title . '"',
            body:   "Your advertisement ran from {$ad->starts_at->format('M d')} to {$ad->ends_at->format('M d, Y')} and has now ended. Book a new campaign to continue reaching customers.",
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

    public function orderConfirmed(Order $order): void
    {
        $carName = $order->car?->displayName() ?? 'your car';

        $this->create(
            userId: $order->buyer_id,
            type:   'order_confirmed',
            title:  'Order confirmed — ' . $carName,
            body:   'The seller has confirmed your order. Please arrange payment to complete the purchase.',
            url:    route('buyer.orders.show', $order->id),
        );
    }

    public function orderCompleted(Order $order): void
    {
        $carName = $order->car?->displayName() ?? 'your car';

        $this->create(
            userId: $order->buyer_id,
            type:   'order_completed',
            title:  'Purchase complete — ' . $carName,
            body:   'Your order has been marked as completed. Congratulations on your new car!',
            url:    route('buyer.orders.show', $order->id),
        );
    }

    public function orderCancelledBySeller(Order $order): void
    {
        $carName = $order->car?->displayName() ?? 'your car';

        $this->create(
            userId: $order->buyer_id,
            type:   'order_cancelled',
            title:  'Order cancelled — ' . $carName,
            body:   'The seller has cancelled your order. The listing may be available again.',
            url:    route('buyer.orders.index'),
        );
    }

    /**
     * Fired when a seller confirms a different buyer's order for the same
     * single-stock listing. The buyer whose order is now sold_out is notified
     * immediately so they are not left waiting.
     */
    public function orderSoldOut(Order $order): void
    {
        $carName = $order->carDisplayName();

        $this->create(
            userId: $order->buyer_id,
            type:   'order_sold_out',
            title:  'Car no longer available — ' . $carName,
            body:   'Unfortunately the seller has confirmed another buyer\'s order for this car. '
                  . 'This listing is now sold out and your order has been closed. '
                  . 'Browse other listings to find your next car.',
            url:    route('buyer.orders.show', $order->id),
        );
    }

    /**
     * Fired when a previously confirmed order is cancelled, making the car
     * available again. Any buyers whose orders were set to sold_out are
     * notified that their order is back in pending state.
     */
    public function orderReinstated(Order $order): void
    {
        $carName = $order->carDisplayName();

        $this->create(
            userId: $order->buyer_id,
            type:   'order_reinstated',
            title:  'Good news — ' . $carName . ' is available again',
            body:   'The confirmed order for this car was cancelled. Your order is now back to pending '
                  . 'and the seller can review it. Stay tuned!',
            url:    route('buyer.orders.show', $order->id),
        );
    }

    // ── Pre-Order ──────────────────────────────────────────────────────

    public function preOrderDepositConfirmed(PreOrder $preOrder): void
    {
        $carName = $preOrder->car?->displayName() ?? 'your pre-ordered car';

        $this->create(
            userId: $preOrder->buyer_id,
            type:   'preorder_deposit_confirmed',
            title:  'Deposit confirmed — ' . $carName,
            body:   'Your deposit has been received and confirmed by the seller. We will notify you when the car arrives.',
            url:    route('buyer.preorders.show', $preOrder->id),
        );
    }

    public function preOrderConverted(PreOrder $preOrder): void
    {
        $carName = $preOrder->car?->displayName() ?? 'your pre-ordered car';

        $this->create(
            userId: $preOrder->buyer_id,
            type:   'preorder_converted',
            title:  'Your car has arrived — ' . $carName,
            body:   'Your pre-ordered car is now available. Your order has been moved to confirmed orders.',
            url:    route('buyer.orders.show', $preOrder->order_id),
        );
    }

    public function preOrderCancelledBySeller(PreOrder $preOrder): void
    {
        $carName = $preOrder->car?->displayName() ?? 'your pre-ordered car';

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

        \Illuminate\Support\Facades\Cache::forget("user_unread_notifications_{$userId}");
    }
}