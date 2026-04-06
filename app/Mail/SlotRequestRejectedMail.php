<?php

namespace App\Mail;

use App\Models\EvStationSlot;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SlotRequestRejectedMail extends Mailable
{
    public function __construct(
        public EvStationSlot $slot,
        public User $customer,
        public string $reason = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on your EV slot request — BijuliCar'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.slot-rejected'
        );
    }
}