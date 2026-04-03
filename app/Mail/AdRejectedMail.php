<?php

namespace App\Mail;

use App\Models\Advertisement;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Sent when admin rejects an ad submission with a reason.
 */
class AdRejectedMail extends Mailable
{
    public function __construct(public Advertisement $advertisement) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on your advertisement submission'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ad-rejected'
        );
    }
}