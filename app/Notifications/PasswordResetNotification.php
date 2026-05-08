<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = Route::has('password.reset')
            ? route('password.reset', ['token' => $this->token, 'email' => $notifiable->email])
            : url('/login');

        return (new MailMessage)
            ->subject('Password Reset Request')
            ->view('emails.password-reset', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
            ]);
    }
}
