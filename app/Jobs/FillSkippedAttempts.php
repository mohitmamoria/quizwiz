<?php

namespace App\Jobs;

use App\Models\Attempt;
use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FillSkippedAttempts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $question = $this->game->currentQuestion;

        $allUsers = $this->game->users->pluck('id');

        $usersWhoAttempted = $this->game->attempts()->forQuestion($question)->get()->pluck('user_id');

        $usersWhoSkipped = $allUsers->diff($usersWhoAttempted)->values();

        foreach ($usersWhoSkipped as $userId) {
            Attempt::updateOrCreate(
                ['game_id' => $this->game->id, 'question_id' => $question->id, 'user_id' => $userId],
                [
                    'answer' => '--skipped--',
                    'evaluated_at' => now(),
                    'is_correct' => false,
                    'health_spent' => 10,
                    'time_spent' => 60,
                ],
            );
        }
    }
}
