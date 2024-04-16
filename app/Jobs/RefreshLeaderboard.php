<?php

namespace App\Jobs;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RefreshLeaderboard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CHUNK_SIZE = 250;

    protected int $lastRank = 0;

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
        DB::transaction(function () {
            $this->game->users()
                ->withSum(['attempts as total_health_spent' => fn ($query) => $query->where('game_id', $this->game->id)], 'health_spent')
                ->withSum(['attempts as total_time_spent' => fn ($query) => $query->where('game_id', $this->game->id)], 'time_spent')
                ->orderBy('total_time_spent')
                ->orderBy('total_health_spent')
                ->chunk(static::CHUNK_SIZE, function (Collection $links) {
                    $this->storeInLeaderboard($links);
                });
        });
    }

    protected function storeInLeaderboard(Collection $users)
    {
        $users = $users->mapWithKeys(function ($user) {
            return [
                $user->id => [
                    'health' => 100 - (int) $user->total_health_spent,
                    'time_spent' => (int) $user->total_time_spent,
                    'rank' => ++$this->lastRank,
                ],
            ];
        })->toArray();

        $this->game->users()->sync($users);
    }
}
