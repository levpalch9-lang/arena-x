<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function index(): View
    {
        $tournaments = Tournament::query()
            ->with('game')
            ->whereIn('status', ['published', 'registration_closed', 'ongoing', 'completed'])
            ->orderByRaw("CASE WHEN status = 'published' THEN 0 WHEN status = 'ongoing' THEN 1 ELSE 2 END")
            ->orderBy('starts_at')
            ->get();

        return view('tournaments.index', ['tournaments' => $tournaments, 'demoMode' => false]);
    }

    public function show(Tournament $tournament): View
    {
        $tournament->load(['game', 'organizer', 'matches.teamA', 'matches.teamB', 'matches.winner']);
        $registrationCount = $tournament->registrations()->whereIn('status', ['pending', 'accepted'])->count();
        $userRegistration = null;

        if (auth()->check()) {
            $user = auth()->user();
            $userRegistration = $tournament->registrations()->where(function ($query) use ($user): void {
                $query->where('user_id', $user->id);
                if ($user->isCaptain()) {
                    $query->orWhereIn('team_id', $user->captainedTeams()->select('id'));
                }
            })->latest()->first();
        }

        return view('tournaments.show', compact('tournament', 'registrationCount', 'userRegistration'));
    }
}
