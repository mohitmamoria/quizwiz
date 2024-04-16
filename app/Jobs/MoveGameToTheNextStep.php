<?php

namespace App\Jobs;

use App\Models\Game;
use Illuminate\Foundation\Bus\Dispatchable;

class MoveGameToNextQuestion
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
        $nextQuestion = $this->game->currentQuestion->next();

        if ($nextQuestion) {
            $this->game->update([
                'current_question_id' => $nextQuestion->id,
            ]);
        } else {
            $this->game->update([
                'current_question_id' => null,
                'ended_at' => now(),
            ]);
        }
    }
}
