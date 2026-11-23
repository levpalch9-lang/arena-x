<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $data = [
            'user' => $user,
            'teamCount' => Team::query()->where('captain_id', $user->id)->count(),
            'registrationCount' => Registration::query()->where('user_id', $user->id)->count(),
            'tournamentCount' => Tournament::query()->whereIn('status', ['published', 'ongoing'])->count(),
            'managedTournaments' => $user->isOrganizer()
                ? Tournament::query()->where('organizer_id', $user->id)->latest()->limit(5)->get()
                : collect(),
            'pendingRegistrations' => $user->isOrganizer()
                ? Registration::query()->whereHas('tournament', fn ($query) => $query->where('organizer_id', $user->id))->where('status', 'pending')->count()
                : 0,
        ];

        return view('dashboard', $data);
    }
}
