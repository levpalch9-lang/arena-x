<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $term = '%'.mb_strtolower($search).'%';
                $query->where(function ($where) use ($term): void {
                    $where->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(nickname) LIKE ?', [$term]);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.index', [
            'usersCount' => User::count(),
            'teamsCount' => Team::count(),
            'tournamentsCount' => Tournament::count(),
            'matchesCount' => TournamentMatch::count(),
            'pendingCount' => Registration::where('status', 'pending')->count(),
            'users' => $users,
            'userSearch' => $search,
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
