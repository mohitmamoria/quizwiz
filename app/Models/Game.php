<?php

namespace App\Models;

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
}
