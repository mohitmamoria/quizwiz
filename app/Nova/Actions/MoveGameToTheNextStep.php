<?php

namespace App\Nova\Actions;

use App\Jobs\MoveGameToTheNextStep as JobsMoveGameToTheNextStep;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class MoveGameToTheNextStep extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '⏩ Move To The Next Step';

    /**
     * Perform the action on the given models.
     *
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $games)
    {
        foreach ($games as $game) {
            JobsMoveGameToTheNextStep::dispatchSync($game);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
