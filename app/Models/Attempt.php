<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attempt extends Model
{
    use HasFactory, SoftDeletes;

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

    public function scopeForGame(Builder $builder, Game $game): Builder
    {
        return $builder->where('game_id', $game->id);
    }

    public function scopeForQuestion(Builder $builder, Question $question): Builder
    {
        return $builder->where('question_id', $question->id);
    }
}
