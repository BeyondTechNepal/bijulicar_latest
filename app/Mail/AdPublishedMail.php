<?php

namespace App\Mail;

use App\Models\Advertisement;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Sent when admin confirms payment received and publishes the ad.
 * Business is notified their ad is now live.
 */
class AdPublishedMail extends Mailable
{
    public function __construct(public Advertisement $advertisement) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your advertisement is now live on Bijulicar!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ad-published'
        );
    }
}