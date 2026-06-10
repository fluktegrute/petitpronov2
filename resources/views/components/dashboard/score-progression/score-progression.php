<?php

use App\Models\Prediction;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function chartData(): array
    {
        $predictions = Prediction::with('game')
            ->where('predictions.user_id', auth()->id())
            ->whereIn('predictions.status', ['exact', 'trend', 'lost', 'cheated'])
            ->join('games', 'predictions.game_id', '=', 'games.id')
            ->orderBy('games.kickoff_at')
            ->select('predictions.*')
            ->get();

        if ($predictions->isEmpty()) {
            return ['scores' => [], 'labels' => [], 'statuses' => []];
        }

        $cumulative = 0;
        $scores = [0];
        $labels = ['Départ'];
        $statuses = [''];

        foreach ($predictions as $pred) {
            $cumulative += $pred->points;
            $scores[] = $cumulative;
            $labels[] = $pred->game->kickoff_at->format('d/m');
            $statuses[] = $pred->status;
        }

        return compact('scores', 'labels', 'statuses');
    }
};
