<?php

use App\Http\Controllers\GameAuthController;
use App\Models\Game;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome to Quizwiz.';
});

// Auth
Route::post('/g/{game}/auth', [GameAuthController::class, 'start'])->name('game-auth.start');
Route::put('/g/{game}/auth', [GameAuthController::class, 'complete'])->name('game-auth.complete');

Route::get('/games/{game}', function (Game $game) {
    return inertia('Game/Auth', [
        'game' => $game,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
