<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Registration;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TournamentRegistrationController extends Controller
{
    public function store(Request $request, Tournament $tournament): RedirectResponse
    {
        abort_unless($tournament->status === 'published', 422, 'Регистрация закрыта.');
        if ($tournament->registration_starts_at && now()->lt($tournament->registration_starts_at)) {
            return back()->withErrors(['registration' => 'Регистрация ещё не началась.']);
        }
        if ($tournament->registration_ends_at && now()->gt($tournament->registration_ends_at)) {
            return back()->withErrors(['registration' => 'Срок подачи заявок завершён.']);
        }

        $data = $request->validate([
            'team_id' => ['nullable', 'integer', Rule::exists('teams', 'id')],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        return DB::transaction(function () use ($request, $tournament, $data): RedirectResponse {
            $locked = Tournament::query()->lockForUpdate()->findOrFail($tournament->id);
            $user = $request->user();
            $teamId = null;

            if ($locked->participant_type === 'team') {
                $team = $user->captainedTeams()->whereKey($data['team_id'] ?? 0)->first();
                if (! $team) {
                    return back()->withErrors(['team_id' => 'Подать командную заявку может только капитан команды.']);
                }
                $teamId = $team->id;
            }

            $duplicate = $locked->registrations()
                ->where(function ($query) use ($user, $teamId): void {
                    $query->where('user_id', $user->id);
                    if ($teamId) {
                        $query->orWhere('team_id', $teamId);
                    }
                })
                ->whereNotIn('status', ['rejected', 'withdrawn'])
                ->exists();

            if ($duplicate) {
                return back()->withErrors(['registration' => 'Заявка уже существует.']);
            }

            $count = $locked->registrations()->whereIn('status', ['pending', 'accepted'])->count();
            if ($count >= $locked->max_participants) {
                return back()->withErrors(['registration' => 'Все места на турнир заняты.']);
            }

            Registration::create([
                'tournament_id' => $locked->id,
                'user_id' => $user->id,
                'team_id' => $teamId,
                'status' => 'pending',
                'note' => $data['note'] ?? null,
            ]);

            return back()->with('success', 'Заявка отправлена организатору.');
        });
    }

    public function review(Request $request, Registration $registration): RedirectResponse
    {
        $user = $request->user();
        $registration->load('tournament');
        abort_unless($user->isOrganizer() && ($user->isAdmin() || $registration->tournament->organizer_id === $user->id), 403);
        abort_unless($registration->status === 'pending', 422);

        $data = $request->validate(['status' => ['required', Rule::in(['accepted', 'rejected'])]]);

        DB::transaction(function () use ($registration, $data, $user): void {
            $registration->update(['status' => $data['status'], 'reviewed_by' => $user->id, 'reviewed_at' => now()]);
            if ($registration->user_id) {
                Notification::create([
                    'user_id' => $registration->user_id,
                    'title' => $data['status'] === 'accepted' ? 'Заявка одобрена' : 'Заявка отклонена',
                    'body' => 'Решение по турниру «'.$registration->tournament->title.'» обновлено.',
                ]);
            }
        });

        return back()->with('success', 'Решение по заявке сохранено.');
    }
}
