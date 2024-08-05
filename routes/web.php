<?php

use App\Http\Controllers\GameAuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SoloGameController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    if (auth()->user() && !auth()->user()->can('viewNova')) {
        auth()->logout();
    }

    return inertia('Home');
})->name('home');

// Auth
Route::get('/g/{game}/auth', [GameAuthController::class, 'show'])->name('game-auth.show');
Route::post('/g/{game}/auth', [GameAuthController::class, 'start'])->name('game-auth.start');
Route::put('/g/{game}/auth', [GameAuthController::class, 'complete'])->name('game-auth.complete');

// Join
Route::post('/g/verify-code', [GameController::class, 'verifyCode'])->name('game.verify-code');
Route::get('/g/{game}/joining-info', [GameController::class, 'joiningInfo'])->name('game.joining-info');
Route::get('/g/{game:joining_code}/join', [GameController::class, 'join'])->name('game.join');
Route::middleware(['auth', 'can:move-next-in-game,game'])->group(function () {
    Route::get('/g/{game}/leaderboard', [GameController::class, 'leaderboard'])->name('game.leaderboard');
    Route::post('/g/{game}/next', [GameController::class, 'next'])->name('game.next');
});

// Play
Route::middleware(['auth'])->group(function () {
    Route::get('/g/{game}/instructions', [GameController::class, 'instructions'])->name('game.instructions');
    Route::get('/g/{game}/play', [GameController::class, 'play'])->name('game.play');
    Route::post('/g/{game}/answers', [GameController::class, 'submitAnswerToCurrentQuestion'])->name('game.submit-answer');
});

// Play Solo Games
Route::get('q/{quiz}/solo/create', [SoloGameController::class, 'create'])->name('solo-game.create');
Route::post('quizzes/{quiz}/solo', [SoloGameController::class, 'store'])->name('solo-game.store');

Route::get('/login-as-admin', function () {
    auth()->logout();
    auth()->loginUsingId(1, true);

    return 'welcome.';
});
