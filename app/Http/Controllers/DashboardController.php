<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $managedQuery = Registration::query()
            ->with(['tournament', 'user', 'team'])
            ->where('status', 'pending')
            ->whereHas('tournament', function ($query) use ($user): void {
                if (! $user->isAdmin()) {
                    $query->where('organizer_id', $user->id);
                }
            });

        return view('dashboard', [
            'user' => $user,
            'teamCount' => Team::query()->where('captain_id', $user->id)->count(),
            'registrationCount' => Registration::query()->where('user_id', $user->id)->count(),
            'myRegistrations' => Registration::query()->with('tournament')->where('user_id', $user->id)->latest()->limit(8)->get(),
            'notifications' => $user->notifications()->latest()->limit(8)->get(),
            'unreadNotifications' => $user->notifications()->whereNull('read_at')->count(),
            'tournamentCount' => Tournament::query()->whereIn('status', ['published', 'ongoing'])->count(),
            'managedTournaments' => $user->isOrganizer()
                ? Tournament::query()->where('organizer_id', $user->id)->latest()->limit(5)->get()
                : collect(),
            'pendingRegistrations' => $user->isOrganizer() ? (clone $managedQuery)->count() : 0,
            'pendingRegistrationItems' => $user->isOrganizer() ? $managedQuery->latest()->limit(10)->get() : collect(),
            'upcomingMatches' => $user->isOrganizer()
                ? TournamentMatch::with(['tournament', 'teamA', 'teamB'])
                    ->where('status', 'scheduled')
                    ->whereHas('tournament', fn ($query) => $query->where('organizer_id', $user->id))
                    ->orderByRaw('scheduled_at IS NULL')->orderBy('scheduled_at')->limit(8)->get()
                : collect(),
        ]);
    }
}
