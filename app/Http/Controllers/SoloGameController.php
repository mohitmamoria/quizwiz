<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;

class SoloGameController extends Controller
{
    public function create(Request $request, Quiz $quiz)
    {
        if (auth()->user()) {
            auth()->logout();
        }

        return inertia('SoloGame/Auth', [
            'quiz' => $quiz,
        ]);
    }

    public function store(Request $request, Quiz $quiz)
    {
        $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::updateOrCreate([
            'email' => $input['email'],
        ], [
            'name' => $input['name'],
        ]);

        auth()->login($user);

        // If a previous game exists, we resume that
        $game = $user->games()
            ->where('is_solo', true)
            ->where('quiz_id', $quiz->id)
            ->first();
        if (is_null($game)) {
            // Otherwise, we start a new one
            $game = $user->games()->create([
                'quiz_id' => $quiz->id,
                'is_solo' => true,
            ]);
        }

        return redirect()->route('game.instructions', ['game' => $game]);
    }
}
