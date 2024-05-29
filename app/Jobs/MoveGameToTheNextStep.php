<?php

namespace App\Jobs;

use App\Events\GameMadeProgress;
use App\Models\Game;
use Illuminate\Foundation\Bus\Dispatchable;

class MoveGameToTheNextStep
{
    use Dispatchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Game $game)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /**
         * Steps:
         * 1. Start game (shows the first question)
         * 2. Show answer to the previous question and stop accepting any more attempts
         * 3. Show the next question
         * 4. End game
         */
        if (!$this->game->started_at) {
            StartGame::dispatchSync($this->game);

            return;
        }

        // Lock in attempts, and refresh the leaderboard. No more attempts can be submitted.
        FillSkippedAttempts::dispatchSync($this->game);
        RefreshLeaderboard::dispatchSync($this->game);

        // If showing the question, we'll reveal it's answer next.
        if (is_null($this->game->current_question_answered_at)) {
            $this->game->update([
                'current_question_answered_at' => now(),
            ]);
        } else {
            $nextQuestion = $this->game->currentQuestion->next();

            if ($nextQuestion) {
                $this->game->update([
                    'current_question_id' => $nextQuestion->id,
                    'current_question_asked_at' => now(),
                    'current_question_answered_at' => null,
                ]);
            } else {
                EndGame::dispatchSync($this->game);
            }
        }

        GameMadeProgress::dispatch($this->game);
    }
}
