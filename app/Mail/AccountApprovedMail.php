<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccountApprovedMail extends Mailable
{
    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Bijulicar account has been approved!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-approved'
        );
    }
}