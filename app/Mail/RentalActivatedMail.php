<?php

namespace App\Mail;

use App\Models\CarRental;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class RentalActivatedMail extends Mailable
{
    public function __construct(public CarRental $rental) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your rental is now active — ' . $this->rental->carDisplayName() . ' — BijuliCar'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rental-activated'
        );
    }
}
