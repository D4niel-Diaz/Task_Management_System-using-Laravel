<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountActivityAlert extends Notification
{
    use Queueable;

    public function __construct(protected string $activity, protected array $details = []) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Activity Alert')
            ->view('emails.account-activity', [
                'user' => $notifiable,
                'activity' => $this->activity,
                'details' => $this->details,
            ]);
    }
}
