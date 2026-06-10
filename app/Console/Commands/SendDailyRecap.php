<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;

use App\Notifications\DailyRecapNotification;

use App\Concerns\LogsWithTimestamps;

use Illuminate\Console\Command;

class SendDailyRecap extends Command
{
    use LogsWithTimestamps;

    protected $signature = 'notifications:daily-recap';
    protected $description = 'Envoie le récapitulatif quotidien des résultats et du classement';

    public function handle(): void
    {
        $finishedGames = Game::where('status', 'FINISHED')
            ->whereDate('kickoff_at', today())
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        if($finishedGames->isEmpty()){
            $this->displayLogs("Aucun match terminé aujourd'hui.");
            return;
        }
        $rankedUsers = User::orderByDesc('total_points')
            ->orderByDesc('exact_count')
            ->orderByDesc('trend_count')
            ->orderByDesc('prono_count')
            ->pluck('id');

        $totalUsers = $rankedUsers->count();
        $gameIds = $finishedGames->pluck('id');

        $users = User::where('notify_daily_recap', true)->get();

        foreach($users as $user){
            $userPredictions = Prediction::where('user_id', $user->id)
                ->whereIn('game_id', $gameIds)
                ->whereIn('status', ['exact', 'trend', 'lost', 'cheated'])
                ->get()
                ->keyBy('game_id');

            $pointsEarnedToday = $userPredictions->sum('points');

            $rankIndex   = $rankedUsers->search($user->id);
            $currentRank = $rankIndex !== false ? $rankIndex + 1 : $totalUsers;

            $user->notify(new DailyRecapNotification(
                finishedGames:    $finishedGames,
                userPredictions:  $userPredictions,
                pointsEarnedToday: $pointsEarnedToday,
                currentRank:      $currentRank,
                totalUsers:       $totalUsers,
            ));

            $this->displayLogs("Récap envoyé à {$user->name} (rang #{$currentRank}).");
        }
    }
}
