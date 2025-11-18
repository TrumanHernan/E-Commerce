<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification with the custom design.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);
        $passwordBroker = config('auth.defaults.passwords');
        $expiration = config("auth.passwords.{$passwordBroker}.expire");

        return (new MailMessage)
            ->subject('Recupera tu acceso a ' . config('app.name', 'NutriShop'))
            ->view('emails.password-reset', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'expiration' => $expiration,
                'appName' => config('app.name', 'NutriShop'),
                'supportEmail' => config('mail.from.address'),
            ]);
    }
}
