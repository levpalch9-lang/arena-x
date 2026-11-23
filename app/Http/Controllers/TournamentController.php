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
        $tournament->setRelation('matches', $tournament->matches->sortBy(['round_number', 'match_number'])->values());
        $standings = $acceptedTeams->map(function ($team) use ($tournament) {
            $teamMatches = $tournament->matches->where('status', 'completed')
                ->filter(fn ($match) => (int) $match->team_a_id === (int) $team->id || (int) $match->team_b_id === (int) $team->id);
            $wins = $teamMatches->filter(fn ($match) => (int) $match->winner_team_id === (int) $team->id)->count();
            $for = $teamMatches->sum(fn ($match) => (int) ((int) $match->team_a_id === (int) $team->id ? $match->score_a : $match->score_b));
            $against = $teamMatches->sum(fn ($match) => (int) ((int) $match->team_a_id === (int) $team->id ? $match->score_b : $match->score_a));
            return ['team' => $team, 'played' => $teamMatches->count(), 'wins' => $wins, 'losses' => $teamMatches->count() - $wins, 'difference' => $for - $against];
        })->sortBy(fn ($row) => [-$row['wins'], -$row['difference']])->values();
        $acceptedTeams = $tournament->registrations()->with('team')->where('status', 'accepted')->whereNotNull('team_id')->get()->pluck('team')->filter()->unique('id')->values();
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

        return view('tournaments.show', compact('tournament', 'registrationCount', 'userRegistration', 'acceptedTeams', 'standings'));
    }
}
