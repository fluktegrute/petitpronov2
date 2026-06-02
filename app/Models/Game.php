<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'kickoff_at' => 'datetime:UTC', 
    ];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    protected function statusFr(): Attribute
    {
        return Attribute::make(
            get: fn () => __('api.status.' . $this->status)
        );
    }

    protected function stageFr(): Attribute
    {
        return Attribute::make(
            get: fn () => __('api.stage.' . $this->stage)
        );
    }

    protected function groupFr(): Attribute 
    {
        return Attribute::make(
            get: fn () => __('api.group.' . $this->group)
        );
    }

    protected function formattedDate(): Attribute 
    {
        return Attribute::make(
            get: fn () => ucfirst(Carbon::parse($this->kickoff_at)->timezone(config('app.timezone'))->translatedFormat('l d F Y \à H\hi'))
        );
    }
}
