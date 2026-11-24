<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'users' => User::latest()->limit(12)->get(),
            'tournaments' => Tournament::with('game')->latest()->limit(8)->get(),
            'pendingRegistrations' => Registration::with(['tournament', 'team', 'user'])->where('status', 'pending')->latest()->limit(20)->get(),
            'gamesCount' => Game::count(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->id === $user->id, 422, 'Нельзя изменить собственную роль из этой панели.');

        $data = $request->validate([
            'role' => ['required', Rule::in(['player', 'captain', 'organizer', 'admin'])],
        ]);

        $user->update(['role' => $data['role']]);

        return back()->with('success', 'Роль пользователя '.$user->nickname.' обновлена.');
    }
}
