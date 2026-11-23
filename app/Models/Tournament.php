<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    public function getRouteKeyName(): string { return 'slug'; }

    protected $fillable = ['game_id', 'organizer_id', 'title', 'slug', 'description', 'rules', 'format', 'participant_type', 'team_size', 'max_participants', 'prize_pool', 'registration_starts_at', 'registration_ends_at', 'starts_at', 'status'];
    protected function casts(): array
    {
        return ['registration_starts_at' => 'datetime', 'registration_ends_at' => 'datetime', 'starts_at' => 'datetime'];
    }
    public function game() { return $this->belongsTo(Game::class); }
    public function organizer() { return $this->belongsTo(User::class, 'organizer_id'); }
    public function registrations() { return $this->hasMany(Registration::class); }
    public function matches() { return $this->hasMany(TournamentMatch::class, 'tournament_id'); }
}
