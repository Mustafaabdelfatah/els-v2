<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as NotificationsResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends NotificationsResetPassword
{

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = config('app.url') . '/auth/password/reset/?_token=' . $this->token . '&email=' . urlencode($notifiable->email);
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset password', $url)
            ->line('If you did not request password reset , not further action is required.');
    }
}
