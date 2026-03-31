<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccountRejectedMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on your Bijulicar account application'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-rejected'
        );
    }
}