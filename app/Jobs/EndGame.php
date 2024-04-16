<?php

namespace App\Jobs;

use App\Events\GameMadeProgress;
use App\Models\Game;
use Illuminate\Foundation\Bus\Dispatchable;

class EndGame
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
        FillSkippedAttempts::dispatchSync($this->game);
        RefreshLeaderboard::dispatchSync($this->game);

        $this->game->update([
            'ended_at' => now(),
        ]);

        GameMadeProgress::dispatch($this->game);
    }
}
