<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\MimeTypes;

use Carbon\Carbon;

use App\Services\ApiService;

use App\Concerns\LogsWithTimestamps;

use App\Models\Game;
use App\Models\Team;
use App\Models\Prediction;
use App\Models\User;

#[Description("Met à jour les matches depuis l'API football-data.org")]

class UpdateMatches extends Command
{
    use LogsWithTimestamps;

    protected $signature = "matches:update {--fake : Simule l'appel API avec de faux résultats}";

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->displayLogs("Starting update");

        if($this->option('fake')) {
            $this->displayLogs("Fake mode: API calls will be simulated.");

            Http::fake([
                config('services.football_data.url').'*' => Http::response($this->generateFakeResponse(), 200)
            ]);
        }

        $all_matches = ApiService::getEndpoint('matches');

        if (!$all_matches || !isset($all_matches['matches'])) {
            $this->fail("Unable to get data from API.");
        }

        foreach ($all_matches['matches'] as $match) {
            if($match['homeTeam']['id'] && $match['awayTeam']['id']){
                $this->displayLogs("Updating {$match['homeTeam']['name']} vs {$match['awayTeam']['name']}");

                $homeTeam = Team::where('api_id', $match['homeTeam']['id'])->first();
                if(!$homeTeam) {
                    $this->displayLogs("Unable to find team '{$match['homeTeam']['name']}', creating...");
                    $homeTeam = $this->createTeam($match, 'homeTeam');
                }
                
                $awayTeam = Team::where('api_id', $match['awayTeam']['id'])->first();
                if(!$awayTeam) {
                    $this->displayLogs("Unable to find team '{$match['awayTeam']['name']}', creating...");
                    $awayTeam = $this->createTeam($match, 'awayTeam');
                }

                if (!$homeTeam || !$awayTeam) {
                    $this->displayLogs("Unable to retrieve or create at least one team, ignoring game", "error");
                    continue;
                }

                $bracketSlot = $this->determineBracketSlot($match, $homeTeam, $awayTeam);

                $game = Game::firstOrNew(['api_id' => $match['id']]);

                $game->fill([
                    'kickoff_at'   => Carbon::parse($match['utcDate'])->format('Y-m-d H:i:s'),
                    'status'       => $game->status === 'FINISHED' ? 'FINISHED' : $match['status'],
                    'stage'        => $match['stage'],
                    'group'        => $match['group'],
                    'bracket_slot' => $bracketSlot,
                    'matchday'     => $match['matchday'],
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                ])->save();

                $this->displayLogs('Match updated');

                $apiHomeScore = $match['score']['fullTime']['home'];
                $apiAwayScore = $match['score']['fullTime']['away'];
                $apiWinner    = $match['score']['winner'];

                $apiScoresValid = !is_null($apiHomeScore) && !is_null($apiAwayScore) && (
                    ($apiHomeScore > $apiAwayScore && $apiWinner === 'HOME_TEAM') ||
                    ($apiHomeScore < $apiAwayScore && $apiWinner === 'AWAY_TEAM') ||
                    ($apiHomeScore === $apiAwayScore && $apiWinner === 'DRAW')
                );

                if($game->status === 'FINISHED' && $apiScoresValid && is_null($game->home_score)){
                    $game->update([
                        'home_score'     => $apiHomeScore,
                        'away_score'     => $apiAwayScore,
                        'winner_team_id' => $apiWinner === 'HOME_TEAM' ? $homeTeam->id : ($apiWinner === 'AWAY_TEAM' ? $awayTeam->id : null),
                    ]);
                    $game->refresh();

                    $this->displayLogs('Match newly finished, updating teams score and points');

                    $this->updateTeamStats($homeTeam, $match['score'], 'home');
                    $this->updateTeamStats($awayTeam, $match['score'], 'away');

                    if($game->group)
                        $this->updateTeamsRankings($game->group);

                    $this->displayLogs('Teams stats updated, updating players predictions...');

                    $gameTrend = $game->home_score <=> $game->away_score;

                    $multiplierValue    = config('app.booster_multiplier');
                    $cheatingPoints     = config('app.cheating_points_to_remove');
                    $finalWinnerPoints  = config('app.winner_prono_points');

                    if(is_null($game->odds_home) || is_null($game->odds_away) || is_null($game->odds_draw)){
                        $winningPoints      = config('app.winning_prono_points');
                        $exactPoints        = config('app.exact_score_points');
                    }
                    else{
                        $winningOdd = match($gameTrend) {
                            1  => $game->odds_home,
                            0  => $game->odds_draw,
                            -1 => $game->odds_away,
                        };
                        $winningPoints = (int) round($winningOdd * config('app.winning_prono_odds_multiplier'));
                        $exactPoints   = (int) round($winningOdd * config('app.exact_score_odds_multiplier'));
                    }

                    $predictions = Prediction::with('user')->where('game_id', $game->id)->get();
                    
                    foreach($predictions as $prediction) {
                        if ($prediction->status === 'cheated')
                            $prediction->points = -$cheatingPoints;
                        else {
                            $multiplier = $prediction->is_boosted ? $multiplierValue : 1;
                            $predictionTrend = $prediction->home_score <=> $prediction->away_score;

                            // Score exact
                            if ($prediction->home_score === $game->home_score && $prediction->away_score === $game->away_score) {
                                $prediction->points = $exactPoints * $multiplier;
                                $prediction->status = 'exact';
                            } 
                            // Bon prono 
                            elseif ($predictionTrend === $gameTrend) {
                                $prediction->points = $winningPoints * $multiplier;
                                $prediction->status = 'trend';

                            } 
                            // Mauvais prono
                            else {
                                $prediction->points = 0;
                                $prediction->status = 'lost';
                            }

                        }
                        
                        if($game->stage == 'FINAL' && $game->winner_team_id == $prediction->user->winner_team_id)
                            $prediction->points += $finalWinnerPoints;

                        $prediction->save();
                    }
                }

                $this->displayLogs('Users predictions updated. Calculating players total score...');

                $userIds = Prediction::where('game_id', $game->id)->pluck('user_id')->unique();

                foreach ($userIds as $userId) {
                    $stats = Prediction::where('user_id', $userId)
                        ->selectRaw("
                            COALESCE(SUM(points), 0) as total_points,
                            SUM(CASE WHEN status = 'exact' THEN 1 ELSE 0 END) as exact_count,
                            SUM(CASE WHEN status = 'trend' THEN 1 ELSE 0 END) as trend_count,
                            SUM(CASE WHEN status != 'placed' THEN 1 ELSE 0 END) as prono_count
                        ")
                        ->first();

                    User::where('id', $userId)->update([
                        'total_points' => $stats->total_points,
                        'exact_count'  => $stats->exact_count ?? 0,
                        'trend_count'  => $stats->trend_count ?? 0,
                        'prono_count'  => $stats->prono_count ?? 0,
                    ]);
                }

                $this->displayLogs('Done');
            }
        }
    }

    private function createTeam(array $match, string $team): ?Team
    {
        $response = Http::get($match[$team]['crest']);
        if ($response->successful()) {

            $contentType = $response->header('Content-Type');
            $mimeTypes = new MimeTypes();
            $extensions = $mimeTypes->getExtensions($contentType);

            $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];
            $extension = in_array($extensions[0] ?? '', $allowedExtensions, true) ? $extensions[0] : 'png';
            $teamId = (int) $match[$team]['id'];
            $filename = 'images/flags/' . $teamId . '.' . $extension;
            Storage::disk('public')->put($filename, $response->body());

            $createdTeam = Team::create([
                'name' => $match[$team]['name'],
                'code' => $match[$team]['tla'],
                'api_id' => $match[$team]['id'],
                'pool' => $match['group'],
                'flag_path' => "storage/$filename",
            ]);
        }
        else{
            $this->displayLogs('Error while creating team: ' . $response->body(), 'error');
            return null;
        }
        $this->displayLogs('Done');
        return $createdTeam;
    }

    private function updateTeamStats(Team $team, array $score, string $side): void
    {
        $goalsFor = $score['fullTime'][$side];
        $goalsAgainst = $score['fullTime'][$side === 'home' ? 'away' : 'home'];

        $isWinner = ($score['winner'] === strtoupper($side) . '_TEAM');
        $isDraw = ($score['winner'] === 'DRAW');
        $isLoser = (!$isWinner && !$isDraw);

        $team->update([
            'played' => $team->played + 1,
            'won' => $team->won + ($isWinner ? 1 : 0),
            'draw' => $team->draw + ($isDraw ? 1 : 0),
            'lost' => $team->lost + ($isLoser ? 1 : 0),
            'points' => $team->points + ($isWinner ? 3 : ($isDraw ? 1 : 0)),
            'goals_for' => $team->goals_for + $goalsFor,
            'goals_against' => $team->goals_against + $goalsAgainst,
            'goal_diff' => ($team->goals_for + $goalsFor) - ($team->goals_against + $goalsAgainst),
        ]);
    }

    private function updateTeamsRankings(?string $pool): void 
    {
        if(!$pool)
            return;

        $teams = Team::where('pool', $pool)->get();

        if($teams->isEmpty())
            return;
            
        // ==========================================
        // ÉTAPE 1 : Tri global de la poule
        // ==========================================
        $sortedTeams = $teams->sort(function ($a, $b) {
            // 1. Points
            if ($a->points !== $b->points) return $b->points <=> $a->points;
            // 2. Différence de buts
            if ($a->goal_diff !== $b->goal_diff) return $b->goal_diff <=> $a->goal_diff;
            // 3. Buts marqués
            return $b->goals_for <=> $a->goals_for;
        })->values();

        // ==========================================
        // PRÉPARATION ÉTAPE 2 : Regroupement des égalités
        // ==========================================
        // On regroupe les équipes qui ont exactement les mêmes stats globales
        $tiedBlocks = [];
        foreach ($sortedTeams as $team) {
            // On crée une clé unique basée sur les 3 critères globaux
            $key = "{$team->points}_{$team->goal_diff}_{$team->goals_for}";
            $tiedBlocks[$key][] = $team;
        }

        // ==========================================
        // ÉTAPE 2 : Résolution des égalités (Mini-championnat)
        // ==========================================
        $finalSortedPool = collect();

        foreach ($tiedBlocks as $key => $tiedTeams) {
            // S'il n'y a pas d'égalité (1 seule équipe dans le bloc), on l'ajoute directement
            if (count($tiedTeams) === 1) {
                $finalSortedPool->push($tiedTeams[0]);
                continue;
            }

            // --- DEBUT DU DEPARTAGE H2H ---
            $tiedTeamIds = collect($tiedTeams)->pluck('id')->toArray();

            // On initialise les stats du mini-championnat à 0 pour ces équipes
            $miniLeagueStats = [];
            foreach ($tiedTeamIds as $id) {
                $miniLeagueStats[$id] = ['points' => 0, 'goal_diff' => 0, 'goals_for' => 0];
            }

            // On récupère UNIQUEMENT les matchs joués entre ces équipes à égalité
            $h2hGames = Game::whereIn('home_team_id', $tiedTeamIds)
                ->whereIn('away_team_id', $tiedTeamIds)
                ->whereNotNull('home_score') // Matchs terminés uniquement
                ->get();

            // On recalcule les stats uniquement sur ces matchs
            foreach ($h2hGames as $game) {
                $homeId = $game->home_team_id;
                $awayId = $game->away_team_id;
                $homeScore = (int) $game->home_score;
                $awayScore = (int) $game->away_score;
                
                $diff = $homeScore - $awayScore;

                // Buts marqués
                $miniLeagueStats[$homeId]['goals_for'] += $homeScore;
                $miniLeagueStats[$awayId]['goals_for'] += $awayScore;

                // Différence de buts
                $miniLeagueStats[$homeId]['goal_diff'] += $diff;
                $miniLeagueStats[$awayId]['goal_diff'] -= $diff;

                // Points
                if ($diff > 0) {
                    $miniLeagueStats[$homeId]['points'] += 3;
                } elseif ($diff < 0) {
                    $miniLeagueStats[$awayId]['points'] += 3;
                } else {
                    $miniLeagueStats[$homeId]['points'] += 1;
                    $miniLeagueStats[$awayId]['points'] += 1;
                }
            }

            // On trie ce bloc d'égalités en utilisant les stats du mini-championnat
            usort($tiedTeams, function($a, $b) use ($miniLeagueStats) {
                $statsA = $miniLeagueStats[$a->id];
                $statsB = $miniLeagueStats[$b->id];

                if ($statsA['points'] !== $statsB['points']) return $statsB['points'] <=> $statsA['points'];
                if ($statsA['goal_diff'] !== $statsB['goal_diff']) return $statsB['goal_diff'] <=> $statsA['goal_diff'];
                return $statsB['goals_for'] <=> $statsA['goals_for'];
            });

            // On ajoute les équipes départagées à la liste finale du groupe
            foreach ($tiedTeams as $t) {
                $finalSortedPool->push($t);
            }
        }

        // ==========================================
        // SAUVEGARDE : Mise à jour de la base de données
        // ==========================================
        $position = 1;
        foreach ($finalSortedPool as $team) {
            if ($team->position !== $position) {
                $team->update(['position' => $position]);
            }
            $position++;
        }
    }

    private function determineBracketSlot(array $match, Team $homeTeam, Team $awayTeam): ?int
    {
        $stage = $match['stage'];

        if($stage === 'LAST_32'){
            $anchors = $this->getBracketAchors(); 
            $homeSignature = $homeTeam->position . '_' . $homeTeam->pool;
            $awaySignature = $awayTeam->position . '_' . $awayTeam->pool;

            return $anchors[$homeSignature] ?? $anchors[$awaySignature] ?? null;
        }

        if($stage === 'FINAL') return 31;
        if($stage === 'THIRD_PLACE') return 32;

        if(in_array($stage, ['LAST_16', 'QUARTER_FINALS', 'SEMI_FINALS'])){

            $previousGame = Game::where(function($q) use ($homeTeam) {
                    $q->where('home_team_id', $homeTeam->id)
                    ->orWhere('away_team_id', $homeTeam->id);
                })
                ->whereNotNull('bracket_slot')
                ->orderBy('kickoff_at', 'desc') 
                ->first();

            if(!$previousGame){
                return null; 
            }

            $p = $previousGame->bracket_slot;

            return match($stage) {
                'LAST_16'        => 16 + (int) ceil($p / 2),
                'QUARTER_FINALS' => 24 + (int) ceil(($p - 16) / 2),
                'SEMI_FINALS'    => 28 + (int) ceil(($p - 24) / 2),
                default          => null,
            };
        }

        return null;
    }

    private function generateFakeResponse(): array
    {
        $gamesToSimulate = Game::where('kickoff_at', '>', now())->orderBy('kickoff_at')->take(2)->get();

        $fakeMatches = [];

        foreach ($gamesToSimulate as $game) {
            $homeTeamScore = rand(0, 5);
            $awayTeamScore = rand(0, 5);
            $winner = match($homeTeamScore <=> $awayTeamScore){
                -1  => "AWAY_TEAM",
                0   => "DRAW",
                1   => "HOME_TEAM",
            };

            $fakeMatches[] = [
                "area" => [
                    "id" => 2267,
                    "name" => "World",
                    "code" => "INT",
                    "flag" => null,
                ],
                "competition" => [
                    "id" => 2000,
                    "name" => "FIFA World Cup",
                    "code" => "WC",
                    "type" => "CUP",
                    "emblem" => "https://crests.football-data.org/wm26.png",
                ],
                "season" => [
                    "id" => 2398,
                    "startDate" => "2026-06-11",
                    "endDate" => "2026-07-19",
                    "currentMatchday" => 1,
                    "winner" => null,
                ],
                "id" => $game->api_id,
                "utcDate" => Carbon::parse($game->kickoff_at)->format("Y-m-d\TH:i:s\Z"),
                "status" => "FINISHED",
                "matchday" => $game->matchday,
                "stage" => $game->stage,
                "group" => $game->group,
                "lastUpdated" => Carbon::parse($game->updated_at)->format("Y-m-d\TH:i:s\Z"),
                "homeTeam" => [
                    "id" => $game->homeTeam->api_id,
                    "name" => $game->homeTeam->name,
                    "shortName" => $game->homeTeam->name,
                    "tla" => $game->homeTeam->code,
                    "crest" => "https://crests.football-data.org/{$game->homeTeam->api_id}.svg",
                ],
                "awayTeam" => [
                    "id" => $game->awayTeam->api_id,
                    "name" => $game->awayTeam->name,
                    "shortName" => $game->awayTeam->name,
                    "tla" => $game->awayTeam->code,
                    "crest" => "https://crests.football-data.org/{$game->awayTeam->api_id}.svg",
                ],
                "score" => [
                    "winner" => $winner,
                    "duration" => "REGULAR",
                    "fullTime" => [
                        "home" => $homeTeamScore,
                        "away" => $awayTeamScore,
                    ],
                    "halfTime" => [
                        "home" => floor($homeTeamScore / 2),
                        "away" => floor($awayTeamScore / 2),
                    ],
                ],
                "odds" => [
                    "msg" => "Activate Odds-Package in User-Panel to retrieve odds.",
                ],
                "referees" => [],
                ];
        }

        return [
            'matches' => $fakeMatches
        ];
    }

    private function getBracketAchors(): array 
    {
        // Mapping extrait du tableau officiel FIFA 2026
        return [
            // --- BRANCHE GAUCHE ---
            // M74 & M77
            '1_GROUP_E' => 1,  
            '1_GROUP_I' => 2,  
            // M73 & M75
            '2_GROUP_A' => 3,  '2_GROUP_B' => 3, 
            '1_GROUP_F' => 4,  '2_GROUP_C' => 4, 
            // M83 & M84
            '2_GROUP_K' => 5,  '2_GROUP_L' => 5, 
            '1_GROUP_H' => 6,  '2_GROUP_J' => 6, 
            // M81 & M82
            '1_GROUP_D' => 7,  
            '1_GROUP_G' => 8,  

            // --- BRANCHE DROITE ---
            // M76 & M78
            '1_GROUP_C' => 9,  '2_GROUP_F' => 9, 
            '2_GROUP_E' => 10, '2_GROUP_I' => 10,
            // M79 & M80
            '1_GROUP_A' => 11, 
            '1_GROUP_L' => 12, 
            // M86 & M88
            '1_GROUP_J' => 13, '2_GROUP_H' => 13,
            '2_GROUP_D' => 14, '2_GROUP_G' => 14,
            // M85 & M87
            '1_GROUP_B' => 15, 
            '1_GROUP_K' => 16, 
        ];
    }
}
