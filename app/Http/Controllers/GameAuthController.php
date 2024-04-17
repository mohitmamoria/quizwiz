<?php

namespace App\Http\Controllers;

use App\Mail\MagicCode;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Inertia\Response as InertiaResponse;

class GameAuthController extends Controller
{
    public function show(Request $request, Game $game): InertiaResponse
    {
        return inertia('Game/Auth', [
            'game' => $game,
        ]);
    }

    public function start(Request $request, Game $game): Response
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

        Mail::to($user)->send(new MagicCode($user->magicCodes()->create()));

        return response()->noContent();
    }

    public function complete(Request $request, Game $game)
    {
        $input = $request->validate([
            'email' => 'required|email',
            'code' => 'required',
        ]);

        $user = User::where('email', $input['email'])->first();

        $code = $user->magicCodes()->unexpired()->where('code', $input['code'])->first();

        if (! $code) {
            throw ValidationException::withMessages([
                'code' => [
                    'The code provided is invalid.',
                ],
            ]);
        }

        $code->delete();

        $user->games()->attach($game);

        auth()->login($user, true);

        return response()->noContent();
    }
}
