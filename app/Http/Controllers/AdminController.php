<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.index', [
            'usersCount' => User::count(),
            'teamsCount' => Team::count(),
            'tournamentsCount' => Tournament::count(),
            'pendingCount' => Registration::where('status', 'pending')->count(),
            'users' => User::latest()->limit(8)->get(),
            'tournaments' => Tournament::with('game')->latest()->limit(8)->get(),
            'gamesCount' => Game::count(),
        ]);
    }
}
