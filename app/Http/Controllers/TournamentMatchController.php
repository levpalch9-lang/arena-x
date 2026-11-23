<?php

namespace App\\Http\\Controllers;

use App\\Models\\Team;
use App\\Models\\Tournament;
use App\\Models\\TournamentMatch;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\Validation\\Rule;
use Illuminate\\View\\View;

class TournamentMatchController extends Controller
{
    private function authorizeTournament(Request $request, Tournament $tournament): void
    {
        abort_unless($request->user()->isOrganizer()
            && ($request->user()->isAdmin() || $tournament->organizer_id === $request->user()->id), 403);
    }

    public function store(Request $request, Tournament $tournament): RedirectResponse
    {
        $this->authorizeTournament($request, $tournament);

        $data = $request->validate([
            'team_a_id' => ['required', 'integer', Rule::exists('teams', 'id')],
            'team_b_id' => ['required', 'integer', Rule::exists('teams', 'id'), 'different:team_a_id'],
            'round_number' => ['required', 'integer', 'min:1', 'max:32'],
            'match_number' => ['required', 'integer', 'min:1', 'max:256'],
            'scheduled_at' => ['nullable', 'date'],
            'map_name' => ['nullable', 'string', 'max:80'],
        ]);

        $acceptedTeamIds = $tournament->registrations()
            ->where('status', 'accepted')
            ->whereNotNull('team_id')
            ->pluck('team_id');

        abort_unless($acceptedTeamIds->contains((int) $data['team_a_id'])
            && $acceptedTeamIds->contains((int) $data['team_b_id']), 422, 'В матче могут участвовать только команды с принятой заявкой.');

        TournamentMatch::create($data + ['tournament_id' => $tournament->id, 'status' => 'scheduled']);

        return back()->with('success', 'Матч добавлен в расписание.');
    }

    public function updateScore(Request $request, TournamentMatch $match): RedirectResponse
    {
        $match->load('tournament');
        $this->authorizeTournament($request, $match->tournament);

        $data = $request->validate([
            'score_a' => ['required', 'integer', 'min:0', 'max:99'],
            'score_b' => ['required', 'integer', 'min:0', 'max:99', 'different:score_a'],
            'scheduled_at' => ['nullable', 'date'],
            'map_name' => ['nullable', 'string', 'max:80'],
        ]);

        $winnerId = (int) $data['score_a'] > (int) $data['score_b']
            ? $match->team_a_id
            : $match->team_b_id;

        abort_unless($match->team_a_id && $match->team_b_id, 422, 'Сначала назначьте обе команды.');

        $match->update($data + ['winner_team_id' => $winnerId, 'status' => 'completed']);

        return back()->with('success', 'Результат матча сохранён, победитель определён автоматически.');
    }
}
