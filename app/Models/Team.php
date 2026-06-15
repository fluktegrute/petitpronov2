<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    protected function games(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->homeGames->merge($this->awayGames)->sortBy('kickoff_at'),
        );
    }

    protected function flagPath(): Attribute
    {
        return Attribute::make(
            get: function() {
                $raw = $this->attributes['flag_path'] ?? '';
                if (str_starts_with($raw, 'storage/images/flags/')) {
                    return 'flags/' . basename($raw);
                }
                return $raw;
            }
        );
    }

    protected function nameFr(): Attribute
    {
        return Attribute::make(
            get: fn () => __('api.team.' . $this->name),
        );
    }

    protected function poolFr(): Attribute
    {
        return Attribute::make(
            get: fn () => __('api.group.' . $this->pool),
        );
    }
}
