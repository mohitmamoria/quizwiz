<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Game extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Game>
     */
    public static $model = \App\Models\Game::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'joining_code',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Quiz'),

            Text::make('Joining Code'),

            Boolean::make('Is Solo?', 'is_solo'),

            Number::make('Solo Score', function () {
                return $this->is_solo
                    ? $this->users()->first()->gamestate->score
                    : '—';
            }),

            DateTime::make('Started At'),

            DateTime::make('Ended At'),

            BelongsTo::make('Current Question', 'currentQuestion', Question::class)->nullable()->displayUsing(function ($question) {
                return sprintf('[%d] %s', $question->order, $question->body);
            }),

            DateTime::make('Current Question Asked At')->nullable(),

            DateTime::make('Current Question Answered At')->nullable(),

            BelongsToMany::make('Users')->fields(function ($request, $relatedModel) {
                return [
                    Number::make('Score'),
                    Number::make('Health'),
                    Number::make('Time Spent')->sortable(),
                    Number::make('Rank')->sortable(),
                ];
            }),

            HasMany::make('Attempts'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            new Actions\StartGame,
            new Actions\MoveGameToTheNextStep,
            new Actions\RefreshLeaderboard,
            new Actions\EndGame,
        ];
    }
}
