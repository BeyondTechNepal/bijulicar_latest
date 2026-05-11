<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Overrides Laravel's default plain ResetPassword notification
 * with the Bijulicar-branded email blade.
 *
 * Hooked in via App\Models\User::sendPasswordResetNotification().
 */
class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset your Bijulicar password')
            ->view('emails.reset-password', [
                'notifiable' => $notifiable,
                'resetUrl'   => $resetUrl,
            ]);
    }
}