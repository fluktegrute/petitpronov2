<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchHistory extends Model
{
    protected $table = 'matches_history';

    protected $guarded = [];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    protected function tournamentFr(): Attribute
    {
        return Attribute::make(
            get: fn () => __('api.tournament.' . $this->tournament)
        );
    }
}
