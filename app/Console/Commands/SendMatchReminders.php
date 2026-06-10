<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;

use App\Notifications\MatchReminderNotification;

use App\Concerns\LogsWithTimestamps;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SendMatchReminders extends Command
{
    use LogsWithTimestamps;

    protected $signature = 'notifications:match-reminders';
    protected $description = 'Envoie un rappel aux joueurs sans prono pour les matchs qui arrivent bientôt';

    public function handle(): void
    {
        $windowStart = now()->addHours(11);
        $windowEnd   = now()->addHours(13);

        $upcomingGames = Game::whereBetween('kickoff_at', [$windowStart, $windowEnd])
            ->where('status', 'TIMED')
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        if($upcomingGames->isEmpty()){
            $this->displayLogs('Aucun match dans la fenêtre de 12h.');
            return;
        }

        $users = User::where('notify_match_reminder', true)->get();

        foreach($users as $user){
            $gamesToNotify = $upcomingGames->filter(function(Game $game) use ($user){
                $cacheKey = "match_reminder_{$user->id}_{$game->id}";

                if(Cache::has($cacheKey)){
                    return false;
                }

                $hasPrediction = Prediction::where('user_id', $user->id)
                    ->where('game_id', $game->id)
                    ->exists();

                return !$hasPrediction;
            });

            if ($gamesToNotify->isEmpty()) {
                continue;
            }

            $user->notify(new MatchReminderNotification($gamesToNotify));

            foreach($gamesToNotify as $game){
                Cache::put("match_reminder_{$user->id}_{$game->id}", true, now()->addDay());
            }

            $this->displayLogs("Rappel envoyé à {$user->name} pour {$gamesToNotify->count()} match(s).");
        }
    }
}
