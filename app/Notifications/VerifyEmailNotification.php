<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * Overrides Laravel's default plain VerifyEmail notification
 * with the Bijulicar-branded email blade.
 *
 * To activate, add this to App\Models\User:
 *
 *   use App\Notifications\VerifyEmailNotification;
 *
 *   public function sendEmailVerificationNotification(): void
 *   {
 *       $this->notify(new VerifyEmailNotification);
 *   }
 */
class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify your Bijulicar email address')
            ->view('emails.verify-email-notification', [
                'notifiable'      => $notifiable,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}