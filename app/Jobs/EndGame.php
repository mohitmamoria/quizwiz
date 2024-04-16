<?php

namespace App\Jobs;

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
        $this->game->update([
            'ended_at' => now(),
        ]);
    }
}