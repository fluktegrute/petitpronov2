<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class MatchReminderNotification extends Notification
{

    public function __construct(public Collection $games) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = "[Ponybet] Oops, tu as oublié des pronos !";

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.match-reminder', [
                'user' => $notifiable,
                'games' => $this->games,
            ]);
    }
}
