<?php

namespace App\Mail;

use App\Models\CarRental;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class RentalCancelledMail extends Mailable
{
    /**
     * @param  CarRental  $rental
     * @param  'owner'|'renter'  $cancelledBy   Who initiated the cancellation
     * @param  string  $recipientName           Display name of the email recipient
     */
    public function __construct(
        public CarRental $rental,
        public string $cancelledBy,
        public string $recipientName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rental booking cancelled — ' . $this->rental->carDisplayName() . ' — BijuliCar'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rental-cancelled'
        );
    }
}
