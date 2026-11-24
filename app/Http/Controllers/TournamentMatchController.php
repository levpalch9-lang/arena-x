<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $acceptedTeamIds = $tournament->registrations()->where('status', 'accepted')->whereNotNull('team_id')->pluck('team_id');
        abort_unless($acceptedTeamIds->contains((int) $data['team_a_id'])
            && $acceptedTeamIds->contains((int) $data['team_b_id']), 422, 'В матче могут участвовать только команды с принятой заявкой.');
        TournamentMatch::create($data + ['tournament_id' => $tournament->id, 'status' => 'scheduled']);
        return back()->with('success', 'Матч добавлен в расписание.');
    }

    public function updateSchedule(Request $request, TournamentMatch $match): RedirectResponse
    {
        $match->load('tournament');
        $this->authorizeTournament($request, $match->tournament);
        abort_unless($match->status === 'scheduled', 422, 'Нельзя менять расписание завершённого матча.');

        $data = $request->validate([
            'scheduled_at' => ['nullable', 'date'],
            'map_name' => ['nullable', 'string', 'max:80'],
        ]);

        $match->update($data);

        return back()->with('success', 'Расписание матча обновлено.');
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
        abort_unless($match->team_a_id && $match->team_b_id, 422, 'Сначала назначьте обе команды.');
        $winnerId = (int) $data['score_a'] > (int) $data['score_b'] ? $match->team_a_id : $match->team_b_id;

        DB::transaction(function () use ($match, $data, $winnerId): void {
            $lockedMatch = TournamentMatch::query()->lockForUpdate()->findOrFail($match->id);
            $previousWinnerId = $lockedMatch->winner_team_id;
            if ($lockedMatch->round_number < 32) {
                $nextRound = $lockedMatch->round_number + 1;
                $nextMatchNumber = (int) ceil($lockedMatch->match_number / 2);
                $slot = $lockedMatch->match_number % 2 === 1 ? 'team_a_id' : 'team_b_id';
                $nextMatch = TournamentMatch::query()->where('tournament_id', $lockedMatch->tournament_id)
                    ->where('round_number', $nextRound)->where('match_number', $nextMatchNumber)
                    ->lockForUpdate()->first();

                if ($nextMatch && $nextMatch->{$slot} !== null
                    && (int) $nextMatch->{$slot} !== (int) $winnerId
                    && (int) $nextMatch->{$slot} !== (int) $previousWinnerId) {
                    abort(422, 'Место победителя в следующем раунде уже занято другой командой.');
                }
                if (! $nextMatch) {
                    $nextMatch = new TournamentMatch([
                        'tournament_id' => $lockedMatch->tournament_id,
                        'round_number' => $nextRound,
                        'match_number' => $nextMatchNumber,
                        'status' => 'scheduled',
                    ]);
                }
                $nextMatch->{$slot} = $winnerId;
                $nextMatch->save();
            }
            $lockedMatch->update($data + ['winner_team_id' => $winnerId, 'status' => 'completed']);
        });
        return back()->with('success', 'Результат сохранён. Победитель автоматически переведён в следующий раунд.');
    }
}
