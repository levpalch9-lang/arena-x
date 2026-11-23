<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentManagementController;
use App\Http\Controllers\TournamentRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');
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
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/organizer/tournaments/create', [TournamentManagementController::class, 'create'])->name('organizer.tournaments.create');
    Route::post('/organizer/tournaments', [TournamentManagementController::class, 'store'])->name('organizer.tournaments.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/tournaments/{tournament:slug}/register', [TournamentRegistrationController::class, 'store'])->name('tournaments.register');
    Route::patch('/organizer/registrations/{registration}', [TournamentRegistrationController::class, 'review'])->name('organizer.registrations.review');
    Route::get('/admin', AdminController::class)->middleware('role:admin')->name('admin.index');
});
