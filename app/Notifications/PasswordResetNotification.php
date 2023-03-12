<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordResetNotification extends ResetPassword
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage())
                    ->subject('パスワードリセット通知')
                    ->view('emails.password-reset', [
                        'reset_url' => url(config('app.front_url') . '/auth/resetPassword?token=' . $this->token. '&email=' . urlencode($notifiable->getEmailForPasswordReset()))
                    ]);
    }
}
