<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

use Carbon\Carbon;

use App\Models\Game;

use App\Services\ApiService;

use App\Concerns\LogsWithTimestamps;

class UpdateOdds extends Command
{
    use LogsWithTimestamps;

    protected $signature = 'odds:update';
    protected $description = 'Met à jour les cotes des matchs depuis The Odds API';

    private array $teamAliases = [
        'bosnia-and-herzegovina' => 'bosnia-herzegovina',
        'cape-verde'             => 'cape-verde-islands',
        'usa'                    => 'united-states',
        'czech-republic'         => 'czechia',
        'dr-congo'               => 'congo-dr',        
    ];

    public function handle()
    {
        $this->displayLogs("Starting update");
        
        $this->displayLogs("Fetching odds...");
        
        $oddsData = ApiService::getOdds();

        if(empty($oddsData)){
            $this->displayLogs("Error while fetching odds", 'fail');
        }

        $this->displayLogs("Odds retrived from API, starting matches update");

        foreach($oddsData as $matchOdds){
            $oddsHomeSlug = $this->normalizeTeamName($matchOdds['home_team']);
            $oddsAwaySlug = $this->normalizeTeamName($matchOdds['away_team']);

            $kickoff = Carbon::parse($matchOdds['commence_time'])->format('Y-m-d H:i:s');

            $averagedOdds = $this->calculateAverageOdds($matchOdds['bookmakers'], $matchOdds['home_team'], $matchOdds['away_team']);

            if (!$averagedOdds) {
                $this->displayLogs("No odds available for {$matchOdds['home_team']} vs {$matchOdds['away_team']}", 'warn');
                continue;
            }

            $game = Game::with(['homeTeam', 'awayTeam'])
                ->get()
                ->first(function ($g) use ($oddsHomeSlug, $oddsAwaySlug, $kickoff) {
                    if (!$g->homeTeam || !$g->awayTeam) 
                        return false;

                    $dbHomeSlug = $this->normalizeTeamName($g->homeTeam->name);
                    $dbAwaySlug = $this->normalizeTeamName($g->awayTeam->name);

                    $isSameTeams = ($oddsHomeSlug == $dbHomeSlug && $oddsAwaySlug == $dbAwaySlug);

                    $isSameTimeFrame = Carbon::parse($g->kickoff_at)->diffInHours($kickoff) < 24;

                    return $isSameTeams && $isSameTimeFrame;
                });

            if ($game) {
                $game->update([
                    'odds_home' => $averagedOdds['home'],
                    'odds_draw' => $averagedOdds['draw'],
                    'odds_away' => $averagedOdds['away'],
                ]);
                
                $this->displayLogs("Odds updated for {$game->homeTeam->name} vs {$game->awayTeam->name}");
            } else {
                $this->displayLogs("Unable to find a DB match for API Odds: {$matchOdds['home_team']} vs {$matchOdds['away_team']}", 'warn');
            }
        }
        
        $this->displayLogs("Update finished.");
    }

    private function normalizeTeamName(string $name): string
    {
        $slug = Str::slug($name); 
        return $this->teamAliases[$slug] ?? $slug;
    }

    private function calculateAverageOdds(array $bookmakers, string $homeName, string $awayName): ?array
    {
        $sumHome = 0; 
        $sumDraw = 0; 
        $sumAway = 0;
        $count = 0;

        foreach($bookmakers as $bookmaker){
            foreach($bookmaker['markets'] as $market){
                if($market['key'] === 'h2h'){
                    foreach($market['outcomes'] as $outcome){
                        if($outcome['name'] === $homeName) 
                            $sumHome += $outcome['price'];
                        elseif($outcome['name'] === $awayName) 
                            $sumAway += $outcome['price'];
                        elseif(strtolower($outcome['name']) === 'draw') 
                            $sumDraw += $outcome['price'];
                    }
                    $count++;
                }
            }
        }

        if ($count === 0) return null;

        return [
            'home' => round($sumHome / $count, 2),
            'draw' => round($sumDraw / $count, 2),
            'away' => round($sumAway / $count, 2),
        ];
    }
}