<?php

namespace App\Http\Controllers;

use App\Jobs\EvaluateAttempt;
use App\Jobs\MoveGameToTheNextStep;
use App\Models\Attempt;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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

        if (!$game) {
            return redirect()->back();
        }

        return redirect()->route('game.join', ['game' => $game]);
    }

    public function join(Request $request, Game $game)
    {
        if (!auth()->check()) {
            return redirect()->route('game-auth.show', ['game' => $game]);
        }

        return redirect()->route('game.play', ['game' => $game]);
    }

    public function instructions(Request $request, Game $game): InertiaResponse|RedirectResponse
    {
        // if in middle of game, avoid going back to the instructions page
        if (!is_null($game->started_at)) {
            return redirect()->route('game.play', compact('game'));
        }

        return inertia('Game/Instructions', [
            'user' => $request->user(),
            'game' => $game,
        ]);
    }

    public function play(Request $request, Game $game)
    {
        if ($game->is_solo && is_null($game->started_at)) {
            return redirect()->route('game.instructions', compact('game'));
        }

        $user = $game->users()->where('user_id', $request->user()->id)->latest()->first();

        $game->load('currentQuestion');

        // If the game is marked to show the correct answer, we will append that attribute
        if ($game->current_question_answered_at) {
            $game->currentQuestion->append('correct_answer');
        }

        $attempt = null;
        $wasPreviousAttemptCorrect = false;

        if ($game->started_at) {
            $attempt = $user->attempts()->forGame($game)->forQuestion($game->currentQuestion)->first();

            if ($previousQuestion = $game->currentQuestion->previous()) {
                $wasPreviousAttemptCorrect = $user->attempts()->forGame($game)->forQuestion($previousQuestion)->first()?->is_correct;
            }
        }

        if ($game->ended_at) {
            $game->load('users');
        }

        return inertia('Game/Play', [
            'game' => $game,
            'user' => $user,
            'attempt' => $attempt,
            'wasPreviousAttemptCorrect' => $wasPreviousAttemptCorrect,
            'discountWon' => $this->discountDetails($user, $game),
        ]);
    }

    private function discountDetails(User $user, Game $game)
    {
        if (!$game->ended_at || !$game->is_solo) {
            return null;
        }

        $score = $user->gamestate->score;
        // Offer 10% discount regardless!
        $discountedLink = config(sprintf('discounts.%d', $score > 0 ? $score : 10));

        return [
            'percentage' => $score,
            'discounted_link' => $discountedLink,
            'reason' => match ($score) {
                0 => '🙏 Hard luck! Here\'s a complimentary 10% discount if you want to try your luck at our next show.',
                10 => '🔥 You won 10% discount!',
                20 => '😻 You won 20% discount! That\'s HUGE!',
                30 => '🫡 Salute to you! You won 30% discount.',
                40 => '🚀 WILD PERFORMANCE, you have won whooping 40% discount!',
                50 => '🤯 MY MY MY GOD! You have won the JACKPOT 50% DISCOUNT!'
            }
        ];
    }

    public function submitAnswerToCurrentQuestion(Request $request, Game $game)
    {
        $input = $request->validate([
            'answer' => 'required|max:120',
        ]);

        $user = $game->users()->where('user_id', $request->user()->id)->latest()->first();

        if ($user->gamestate->health < 1) {
            return response()->noContent();
        }

        $currentQuestion = $game->currentQuestion;

        $existing = $user->attempts()->forGame($game)->forQuestion($game->currentQuestion)->first();
        if ($existing) {
            return response()->noContent();
        }

        $attempt = Attempt::updateOrCreate(
            ['game_id' => $game->id, 'question_id' => $currentQuestion->id, 'user_id' => $user->id],
            [
                'answer' => $input['answer'],
                'is_correct' => false,
                'health_spent' => Attempt::HEALTH_SPENT_ON_ATTEMPTING,
                'time_spent' => (int) $game->current_question_asked_at->diffInSeconds(now()),
            ],
        );

        if (!$game->is_solo) {
            EvaluateAttempt::dispatch($attempt);
            return response()->noContent();
        }

        // Solo game must have sync steps
        EvaluateAttempt::dispatchSync($attempt);
        MoveGameToTheNextStep::dispatchSync($game);

        return redirect()->back();
    }

    public function leaderboard(Request $request, Game $game)
    {
        $game->load('users', 'currentQuestion');

        // If the game is marked to show the correct answer, we will append that attribute
        if ($game->current_question_answered_at) {
            $game->currentQuestion->append('correct_answer');
        }

        return inertia('Game/Leaderboard', [
            'game' => $game,
        ]);
    }

    public function next(Request $request, Game $game)
    {
        if ($request->user() && !$request->user()->can('move-next-in-game', $game)) {
            auth()->logout();
        }

        MoveGameToTheNextStep::dispatchSync($game);

        return redirect()->back();
    }
}
