<?php

namespace App\Nova\Actions;

use App\Jobs\CreateTriggersForGame as JobsCreateTriggersForGame;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Timezone;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreateTriggersForGame extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '🤖 Create Triggers';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $games)
    {
        $timestamps = explode(PHP_EOL, $fields->timestamps);

        $startsAt = str($fields->starts_at)->beforeLast('.')->toString();

        foreach ($games as $game) {
            JobsCreateTriggersForGame::dispatchSync(
                $game,
                $startsAt,
                $fields->timezone,
                $timestamps,
            );
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            DateTime::make('Starts At'),

            Timezone::make('Timezone'),

            Textarea::make('Timestamps'),
        ];
    }
}
