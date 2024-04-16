<?php

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Game.{gameId}', function (User $user, int $gameId) {
    // If admin, return true
    if ($user->can('viewNova')) {
        return true;
    }

    // Otherwise, if participating in the game, return true
    return Game::find($gameId)->users()->exists($user);
});
