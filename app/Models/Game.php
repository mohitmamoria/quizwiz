<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'joining_code',
        'is_solo',
        'current_question_id',
        'current_question_asked_at',
        'current_question_answered_at',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'is_solo' => 'boolean',
            'current_question_asked_at' => 'datetime',
            'current_question_answered_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('score', 'health', 'time_spent', 'rank')->as('gamestate')
            ->orderByPivot('rank', 'asc');
    }

    public function currentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function triggers(): HasMany
    {
        return $this->hasMany(Trigger::class)->orderby('due_at');
    }

    public function currentQuestionAggregatedAttempts(): Attribute
    {
        $this->loadCount([
            'attempts as correct_attempts_count' => function (Builder $query) {
                $query->where('question_id', $this->current_question_id)
                    ->whereNotNull('evaluated_at')
                    ->where('is_correct', true);
            },
            'attempts as wrong_attempts_count' => function (Builder $query) {
                $query->where('question_id', $this->current_question_id)
                    ->whereNotNull('evaluated_at')
                    ->where('is_correct', false);
            },
            'attempts as pending_attempts_count' => function (Builder $query) {
                $query->where('question_id', $this->current_question_id)
                    ->whereNull('evaluated_at');
            },
        ]);

        return new Attribute(get: function () {
            return [
                'correct' => $this->correct_attempts_count,
                'wrong' => $this->wrong_attempts_count,
                'pending' => $this->pending_attempts_count,
            ];
        });
    }
}
