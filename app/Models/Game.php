<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['name', 'slug', 'short_name', 'cover_url', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function tournaments() { return $this->hasMany(Tournament::class); }
}
