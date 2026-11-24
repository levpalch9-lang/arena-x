<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentManagementController;
use App\Http\Controllers\TournamentMatchController;
use App\Http\Controllers\TournamentRegistrationController;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredTournaments = Tournament::query()
        ->with('game')
        ->withCount(['registrations as participants_count' => fn ($query) => $query->whereIn('status', ['pending', 'accepted'])])
        ->whereIn('status', ['published', 'ongoing'])
        ->orderByRaw("CASE WHEN status = 'ongoing' THEN 0 ELSE 1 END")
        ->orderBy('starts_at')
        ->limit(3)
        ->get();

    $games = Game::query()->where('is_active', true)->orderBy('name')->get();
    $stats = [
        'tournaments' => Tournament::query()->whereIn('status', ['published', 'registration_closed', 'ongoing', 'completed'])->count(),
        'teams' => \App\Models\Team::query()->count(),
        'matches' => TournamentMatch::query()->count(),
    ];

    $nextMatches = TournamentMatch::query()
        ->with(['tournament.game', 'teamA', 'teamB'])
        ->whereIn('status', ['scheduled', 'live'])
        ->whereHas('tournament', fn ($query) => $query->whereIn('status', ['published', 'registration_closed', 'ongoing']))
        ->orderByRaw('scheduled_at IS NULL')
        ->orderBy('scheduled_at')
        ->limit(4)
        ->get();

    return view('welcome', compact('featuredTournaments', 'games', 'stats', 'nextMatches'));
})->name('home');

Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
Route::get('/tournaments/{tournament:slug}', [TournamentController::class, 'show'])->name('tournaments.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register')->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/members', [TeamController::class, 'addMember'])->name('teams.members.add');
    Route::delete('/teams/{team}/members/{member}', [TeamController::class, 'removeMember'])->name('teams.members.remove');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/tournaments/{tournament:slug}/register', [TournamentRegistrationController::class, 'store'])->name('tournaments.register');
    Route::patch('/organizer/registrations/{registration}', [TournamentRegistrationController::class, 'review'])->middleware('role:admin,organizer')->name('organizer.registrations.review');

    Route::get('/organizer/tournaments/create', [TournamentManagementController::class, 'create'])->middleware('role:admin,organizer')->name('organizer.tournaments.create');
    Route::post('/organizer/tournaments', [TournamentManagementController::class, 'store'])->middleware('role:admin,organizer')->name('organizer.tournaments.store');
    Route::patch('/organizer/tournaments/{tournament:slug}/status', [TournamentManagementController::class, 'changeStatus'])->middleware('role:admin,organizer')->name('organizer.tournaments.status');
    Route::post('/organizer/tournaments/{tournament:slug}/matches', [TournamentMatchController::class, 'store'])->middleware('role:admin,organizer')->name('organizer.matches.store');
    Route::patch('/organizer/matches/{match}/schedule', [TournamentMatchController::class, 'updateSchedule'])->middleware('role:admin,organizer')->name('organizer.matches.schedule');
    Route::patch('/organizer/matches/{match}/score', [TournamentMatchController::class, 'updateScore'])->middleware('role:admin,organizer')->name('organizer.matches.score');

    Route::get('/admin', AdminController::class)->middleware('role:admin')->name('admin.index');
    Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->middleware('role:admin')->name('admin.users.role');
});
