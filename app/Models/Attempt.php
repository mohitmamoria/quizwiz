<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attempt extends Model
{
    use HasFactory, SoftDeletes;

    const HEALTH_SPENT_ON_ATTEMPTING = 0;

    const HEALTH_GAINED_ON_BEING_CORRECT = 0;

    const HEALTH_SPENT_ON_SKIPPING = 0;

    const SCORE_ON_CORRECT = 10;

    const SCORE_ON_INCORRECT = 0;

    const SCORE_ON_SKIPPING = 0;

    const BONUS_TIME_LIMIT_IN_SECONDS = 30;

    protected $fillable = [
        'game_id',
        'question_id',
        'user_id',
        'answer',
        'evaluated_at',
        'is_correct',
        'score',
        'health_spent',
        'time_spent',
    ];

    public function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeForGame(Builder $builder, Game $game): Builder
    {
        return $builder->where('game_id', $game->id);
    }

    public function scopeForQuestion(Builder $builder, Question $question): Builder
    {
        return $builder->where('question_id', $question->id);
    }

    public function secondsSpent(): Attribute
    {
        return new Attribute(get: function () {
            $game = $this->game;


            if ($game->currentQuestion && $game->currentQuestion->is($this->question)) {
                // we will limit the time spent for consideration upto the bonus time limit
                return min(
                    (int) $game->current_question_asked_at->diffInSeconds($this->created_at),
                    static::BONUS_TIME_LIMIT_IN_SECONDS
                );
            }

            // we will consider entire bonus time limit to be spent
            return static::BONUS_TIME_LIMIT_IN_SECONDS;
        });
    }
}
