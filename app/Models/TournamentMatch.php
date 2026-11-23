<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    protected $table = 'matches';
    protected $fillable = ['tournament_id', 'round_number', 'match_number', 'team_a_id', 'team_b_id', 'scheduled_at', 'score_a', 'score_b', 'winner_team_id', 'status', 'map_name'];
    protected function casts(): array { return ['scheduled_at' => 'datetime']; }
    public function tournament() { return $this->belongsTo(Tournament::class); }
    public function teamA() { return $this->belongsTo(Team::class, 'team_a_id'); }
    public function teamB() { return $this->belongsTo(Team::class, 'team_b_id'); }
    public function winner() { return $this->belongsTo(Team::class, 'winner_team_id'); }
}
