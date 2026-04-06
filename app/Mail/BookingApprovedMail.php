<?php

namespace App\Mail;

use App\Models\GarageAppointment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class BookingApprovedMail extends Mailable
{
    public function __construct(public GarageAppointment $appointment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your appointment has been confirmed — BijuliCar'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-approved'
        );
    }
}