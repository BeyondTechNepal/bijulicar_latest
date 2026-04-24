<?php

namespace App\Mail;

use App\Models\CarRental;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class RentalBookingReceivedMail extends Mailable
{
    public function __construct(public CarRental $rental) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New rental booking request — ' . $this->rental->carDisplayName() . ' — BijuliCar'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rental-booking-received'
        );
    }
}
