<?php

namespace App\Jobs;

use App\Enums\TriggerType;
use App\Models\Game;
use App\Models\Trigger;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CreateTriggersForGame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Game $game, protected string $startsAt, protected string $timezone, protected array $timestamps)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startsAt = Carbon::parse($this->startsAt, $this->timezone)->setTimezone(config('app.timezone'));

        $triggers = collect();

        foreach ($this->timestamps as $index => $timestamp) {
            $triggers->push(new Trigger([
                'type' => $index === 0 ? TriggerType::Start : TriggerType::Next,
                'due_at' => $startsAt->clone()->addSeconds($this->timestampToSeconds($timestamp)),
            ]));
        }

        $this->game->triggers()->saveMany($triggers->all());
    }

    protected function timestampToSeconds($timestamp): int
    {
        $ok = preg_match('/([0-9]{2}):([0-9]{2})/', $timestamp, $matches);

        if (!$ok) {
            throw new Exception(sprintf('timestamp (%s) not valid', $timestamp));
        }

        $minutes = (int) $matches[1];
        $seconds = (int) $matches[2];

        return ($minutes * 60) + $seconds;
    }
}
