<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Concerns\LogsWithTimestamps;

use App\Models\MatchHistory;
use App\Models\Team;

#[Description("Importe en base de données l'historique des confrontations entre les équipes depuis le CSV")]
class ImportHead2Head extends Command
{
    use LogsWithTimestamps;
    
    protected $signature = 'h2h:import {dateFrom=1990-01-01}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateFrom = Carbon::parse($this->argument('dateFrom'));

        $this->displayLogs('Starting import');
        
        $filePath = storage_path('app/private/results.csv');
        
        if (!file_exists($filePath)) {
            $this->fail("Unable to find import file : {$filePath}");
        }

        $this->displayLogs("Importing data starting from " . $dateFrom->format('d/m/Y') . " using {$filePath}...");

        $teams = Team::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        })->toArray();

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

        $matchesToInsert = [];
        $count = 0;

        $this->output->progressStart();

        while (($row = fgetcsv($handle)) !== false) {

            $data = array_combine($header, $row);
            $matchDate = Carbon::parse($data['date']);

            // On ignore les vieux matchs et les matches qui n'ont pas encore eu lieu
            if ($matchDate->lt($dateFrom) || $data['home_score'] == 'NA') {
                continue;
            }

            $homeName = strtolower($data['home_team']);
            $awayName = strtolower($data['away_team']);

            // On insère le match UNIQUEMENT si on connaît une des deux équipes
            if (isset($teams[$homeName]) || isset($teams[$awayName])) {
                
                $matchesToInsert[] = [
                    'date'         => $data['date'],
                    'home_team_id' => $teams[$homeName] ?? null,
                    'home_team_name' => $data['home_team'],
                    'away_team_id' => $teams[$awayName] ?? null,
                    'away_team_name' => $data['away_team'],
                    'home_score'   => (int) $data['home_score'],
                    'away_score'   => (int) $data['away_score'],
                    'tournament'   => $data['tournament'],
                    'city'         => $data['city'],
                    'country'      => $data['country'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];

                $count++;
                $this->output->progressAdvance();

                if (count($matchesToInsert) >= 500) {
                    MatchHistory::insert($matchesToInsert);
                    $matchesToInsert = []; 
                }
            }
        }

        if (!empty($matchesToInsert)) {
            MatchHistory::insert($matchesToInsert);
        }

        fclose($handle);
        $this->output->progressFinish();

        $this->info("Import finished. {$count} historical matches added.");

    }
}
