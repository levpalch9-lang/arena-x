<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'game' => ['nullable', 'string', 'max:80'],
            'status' => ['nullable', 'in:published,registration_closed,ongoing,completed'],
            'date' => ['nullable', 'in:upcoming,past'],
        ]);

        $query = Tournament::query()
            ->with('game')
            ->withCount(['registrations as participants_count' => fn ($registrations) => $registrations->whereIn('status', ['pending', 'accepted'])])
            ->whereIn('status', ['published', 'registration_closed', 'ongoing', 'completed']);

        $query->when($filters['q'] ?? null, function ($builder, $search): void {
            $term = '%'.mb_strtolower($search).'%';
            $builder->where(function ($where) use ($term): void {
                $where->whereRaw('LOWER(title) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(description) LIKE ?', [$term])
                    ->orWhereHas('game', fn ($game) => $game->whereRaw('LOWER(name) LIKE ?', [$term]));
            });
        });

        $query->when($filters['game'] ?? null, fn ($builder, $game) => $builder->whereHas('game', fn ($games) => $games->where('slug', $game)));
        $query->when($filters['status'] ?? null, fn ($builder, $status) => $builder->where('status', $status));
        $query->when(($filters['date'] ?? null) === 'upcoming', fn ($builder) => $builder->where(function ($where): void {
            $where->whereNull('starts_at')->orWhere('starts_at', '>=', now());
        }));
        $query->when(($filters['date'] ?? null) === 'past', fn ($builder) => $builder->where('starts_at', '<', now()));

        $tournaments = $query
            ->orderByRaw("CASE WHEN status = 'ongoing' THEN 0 WHEN status = 'published' THEN 1 WHEN status = 'registration_closed' THEN 2 ELSE 3 END")
            ->orderBy('starts_at')
            ->paginate(9)
            ->withQueryString();

        return view('tournaments.index', [
            'tournaments' => $tournaments,
            'games' => Game::query()->where('is_active', true)->orderBy('name')->get(),
            'filters' => $filters,
            'demoMode' => false,
        ]);
    }

    public function show(Tournament $tournament): View
    {
        $tournament->load(['game', 'organizer', 'matches.teamA', 'matches.teamB', 'matches.winner']);
        $tournament->setRelation('matches', $tournament->matches->sortBy(['round_number', 'match_number'])->values());
        $acceptedTeams = $tournament->registrations()->with('team')->where('status', 'accepted')->whereNotNull('team_id')->get()->pluck('team')->filter()->unique('id')->values();
        $standings = $acceptedTeams->map(function ($team) use ($tournament) {
            $teamMatches = $tournament->matches->where('status', 'completed')
                ->filter(fn ($match) => (int) $match->team_a_id === (int) $team->id || (int) $match->team_b_id === (int) $team->id);
            $wins = $teamMatches->filter(fn ($match) => (int) $match->winner_team_id === (int) $team->id)->count();
            $for = $teamMatches->sum(fn ($match) => (int) ((int) $match->team_a_id === (int) $team->id ? $match->score_a : $match->score_b));
            $against = $teamMatches->sum(fn ($match) => (int) ((int) $match->team_a_id === (int) $team->id ? $match->score_b : $match->score_a));
            return ['team' => $team, 'played' => $teamMatches->count(), 'wins' => $wins, 'losses' => $teamMatches->count() - $wins, 'difference' => $for - $against];
        })->sortBy(fn ($row) => [-$row['wins'], -$row['difference']])->values();
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
