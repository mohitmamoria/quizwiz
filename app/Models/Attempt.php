<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attempt extends Model
{
    use HasFactory, SoftDeletes;

    const HEALTH_SPENT_ON_ATTEMPTING = 5;

    const HEALTH_GAINED_ON_BEING_CORRECT = -3;

    const HEALTH_SPENT_ON_SKIPPING = 10;

    protected $fillable = [
        'game_id',
        'question_id',
        'user_id',
        'answer',
        'evaluated_at',
        'is_correct',
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
}
