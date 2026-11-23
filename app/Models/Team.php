<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'tag', 'description', 'logo_url', 'captain_id'];
    public function captain() { return $this->belongsTo(User::class, 'captain_id'); }
    public function members() { return $this->belongsToMany(User::class, 'team_members')->withPivot('member_role', 'joined_at'); }
    public function registrations() { return $this->hasMany(Registration::class); }
}
