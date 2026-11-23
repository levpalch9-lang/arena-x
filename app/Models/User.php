<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'nickname', 'role', 'avatar_url'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOrganizer(): bool { return in_array($this->role, ['admin', 'organizer'], true); }
    public function isCaptain(): bool { return in_array($this->role, ['admin', 'organizer', 'captain'], true); }

    public function teams() { return $this->belongsToMany(Team::class, 'team_members')->withPivot('member_role', 'joined_at'); }
    public function captainedTeams() { return $this->hasMany(Team::class, 'captain_id'); }
    public function registrations() { return $this->hasMany(Registration::class); }
    public function notifications() { return $this->hasMany(Notification::class); }
    public function organizedTournaments() { return $this->hasMany(Tournament::class, 'organizer_id'); }
}
