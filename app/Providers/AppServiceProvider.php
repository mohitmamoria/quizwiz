<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('move-next-in-game', function (User $user, Game $game) {
            if ($user->can('viewNova')) return true;

            // Solo game and the user is participating in the game
            return $game->is_solo && !is_null($game->users()->where('user_id', $user->id)->latest()->first());
        });
    }
}
