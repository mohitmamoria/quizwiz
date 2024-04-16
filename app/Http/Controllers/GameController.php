<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
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

        $attempt = $user->attempts()->forGame($game)->forQuestion($game->currentQuestion)->first();

        return inertia('Game/Play', [
            'game' => $game,
            'user' => $user,
            'attempt' => $attempt,
        ]);
    }

    public function submitAnswerToCurrentQuestion(Request $request, Game $game)
    {
        $input = $request->validate([
            'answer' => 'required|max:120',
            'time_spent' => 'required|numeric',
        ]);

        $user = $game->users()->where('user_id', $request->user()->id)->latest()->first();
        $currentQuestion = $game->currentQuestion;

        $attempt = Attempt::updateOrCreate(
            ['game_id' => $game->id, 'question_id' => $currentQuestion->id, 'user_id' => $user->id],
            [
                'answer' => $input['answer'],
                'is_correct' => false,
                'health_spent' => 5,
                'time_spent' => $input['time_spent'],
            ],
        );

        return response()->noContent();
    }
}
