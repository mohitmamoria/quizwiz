<?php

namespace App\Console\Commands;

use App\Enums\TriggerType;
use App\Jobs\MoveGameToTheNextStep;
use App\Jobs\StartGame;
use App\Models\Trigger;
use Illuminate\Console\Command;

class RunTriggers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-triggers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs all due triggers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(sprintf('== START (%s) ==', now()));

        $dues = Trigger::with('game')->due()->get();

        $this->info(sprintf('Triggers due: %d', $dues->count()));

        foreach ($dues as $trigger) {
            $this->info(sprintf(
                'Trigger ID: %d - Game ID: %d - Type: %s - Due At: %s',
                $trigger->id,
                $trigger->game->id,
                $trigger->type->value,
                $trigger->due_at,
            ));

            switch ($trigger->type) {
                case TriggerType::Start:
                    StartGame::dispatchSync($trigger->game);
                    break;

                case TriggerType::Next:
                    MoveGameToTheNextStep::dispatchSync($trigger->game);
            }

            $trigger->markAsRun();

            $this->info('✅');
        }

        $this->info(sprintf('== END (%s) ==', now()));

        return Command::SUCCESS;
    }
}
