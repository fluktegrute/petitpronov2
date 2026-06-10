<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailyRecapNotification extends Notification
{

    public function __construct(
        public Collection $finishedGames,
        public Collection $userPredictions,
        public int $pointsEarnedToday,
        public int $currentRank,
        public int $totalUsers,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $date = Carbon::today()->locale('fr')->translatedFormat('d F Y');

        return (new MailMessage)
            ->subject("[Ponybet] Récap du {$date}")
            ->view('emails.daily-recap', [
                'user'              => $notifiable,
                'finishedGames'     => $this->finishedGames,
                'userPredictions'   => $this->userPredictions,
                'pointsEarnedToday' => $this->pointsEarnedToday,
                'currentRank'       => $this->currentRank,
                'totalUsers'        => $this->totalUsers,
            ]);
    }
}
