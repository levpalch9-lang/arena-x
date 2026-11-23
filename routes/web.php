<?php

use Illuminate\Support\Facades\Route;

Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');\n\nRoute::get('/', function () {
    return view('welcome');
})->name('home');
