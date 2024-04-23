<?php

namespace App\Nova\Actions;

use App\Jobs\EvaluateAttempt as JobsEvaluateAttempt;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class EvaluateAttempt extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = '✅ Evaluate Attempt';

    /**
     * Perform the action on the given models.
     *
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $attempts)
    {
        foreach ($attempts as $attempt) {
            JobsEvaluateAttempt::dispatch($attempt);
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
