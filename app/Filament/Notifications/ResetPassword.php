<?php

namespace App\Filament\Notifications;

use Filament\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Slaptažodžio atkūrimas')
            ->view('emails.auth.password-reset', [
                'actionUrl' => $this->url,
                'user' => $notifiable,
            ]);
    }
}
