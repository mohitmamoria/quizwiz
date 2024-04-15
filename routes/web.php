<?php

use App\Http\Controllers\GameAuthController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return inertia('Home');
})->name('home');

// Auth
Route::get('/g/{game}/auth', [GameAuthController::class, 'show'])->name('game-auth.show');
Route::post('/g/{game}/auth', [GameAuthController::class, 'start'])->name('game-auth.start');
Route::put('/g/{game}/auth', [GameAuthController::class, 'complete'])->name('game-auth.complete');

// Join
Route::post('/g/verify-code', [GameController::class, 'verifyCode'])->name('game.verify-code');
Route::get('/g/{game}/joining-info', [GameController::class, 'joiningInfo'])->name('game.joining-ingo');
Route::get('/g/{game:joining_code}/join', [GameController::class, 'join'])->name('game.join');
Route::get('/g/{game}/play', [GameController::class, 'play'])->name('game.play')->middleware('auth');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
