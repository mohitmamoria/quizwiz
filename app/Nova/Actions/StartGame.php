<?php

namespace App\Nova\Actions;

use App\Jobs\StartGame as JobsStartGame;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class StartGame extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '🟢 Start Playing';

    /**
     * Perform the action on the given models.
     *
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $games)
    {
        foreach ($games as $game) {
            JobsStartGame::dispatchSync($game);
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
