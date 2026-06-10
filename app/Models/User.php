<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'boosts_remaining', 'winner_team_id', 'total_points', 'prono_count', 'trend_count', 'exact_count', 'avatar_path', 'notify_match_reminder', 'notify_daily_recap'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'      => 'datetime',
            'password'               => 'hashed',
            'notify_match_reminder'  => 'boolean',
            'notify_daily_recap'     => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class)->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function ownedLeagues(): HasMany
    {
        return $this->hasMany(League::class, 'owner_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    protected function totalScore(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->predictions()->sum('points'),
        );
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->role === 'admin',
        );
    }
}
