<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['tournament_id', 'user_id', 'team_id', 'status', 'note', 'reviewed_by', 'reviewed_at'];
    protected function casts(): array { return ['reviewed_at' => 'datetime']; }
    public function tournament() { return $this->belongsTo(Tournament::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function team() { return $this->belongsTo(Team::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
