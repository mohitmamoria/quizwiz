<?php

namespace App\Jobs;

use App\Events\GameMadeProgress;
use App\Models\Game;
use Illuminate\Foundation\Bus\Dispatchable;

class StartGame
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
        $this->game->update([
            'started_at' => now(),
            'current_question_id' => $this->game->quiz->questions()->first()->id,
            'current_question_asked_at' => now(),
            'ended_at' => null,
        ]);

        GameMadeProgress::dispatch($this->game);
    }
}
