<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;

class GameController extends Controller
{
    public function joiningInfo(Request $request, Game $game): InertiaResponse
    {
        return inertia('Game/JoiningInfo', [
            'game' => $game,
        ]);
    }

    public function verifyCode(Request $request)
    {
        $input = $request->validate([
            'code' => 'required',
        ]);

        $game = Game::where('joining_code', $input['code'])->whereNull('ended_at')->first();

        if (! $game) {
            return redirect()->back();
        }

        return redirect()->route('game.join', ['game' => $game]);
    }

    public function join(Request $request, Game $game)
    {
        if (! auth()->check()) {
            return redirect()->route('game-auth.show', ['game' => $game]);
        }

        return redirect()->route('game.play', ['game' => $game]);
    }

    public function play(Request $request, Game $game)
    {
        $user = $game->users()->where('user_id', $request->user()->id)->latest()->first();

        $game->load('currentQuestion');

        return inertia('Game/Play', [
            'game' => $game,
            'user' => $user,
        ]);
    }
}
