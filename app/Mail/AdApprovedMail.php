<?php

namespace App\Mail;

use App\Models\Advertisement;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Sent when admin approves the ad and sets the charged amount.
 * Business receives the amount they owe and payment instructions.
 */
class AdApprovedMail extends Mailable
{
    public function __construct(public Advertisement $advertisement) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ad has been approved — payment required'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ad-approved'
        );
    }
}