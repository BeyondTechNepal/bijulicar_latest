<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\User;
use App\Models\UserNotification;

/**
 * Central service for creating in-app notifications.
 *
 * Each public method maps to one trigger point in a controller.
 * Call these right after (or alongside) the existing Mail::send() calls.
 *
 * Usage example:
 *   app(NotificationService::class)->adApproved($advertisement);
 */
class NotificationService
{
    // ── Advertisement notifications ────────────────────────────────────

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
            body:   'Your advertisement is now published and visible to users.',
            url:    route('business.advertisements.index'),
        );
    }

    // ── EV slot notifications ──────────────────────────────────────────

    public function slotApproved(EvStationSlot $slot, User $customer): void
    {
        $this->create(
            userId: $customer->id,
            type:   'slot_approved',
            title:  'EV slot approved — Port #' . $slot->slot_number,
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

    // ── Garage appointment notifications ──────────────────────────────

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

    // ── Account verification notifications ────────────────────────────

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
    }
}