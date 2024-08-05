<?php

namespace App\Models;

use App\Enums\TriggerType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trigger extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'game_id',
        'type',
        'due_at',
        'run_at',
    ];

    public function casts(): array
    {
        return [
            'type' => TriggerType::class,
            'due_at' => 'datetime',
            'run_at' => 'datetime',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->whereTime('due_at', '<=', now())->whereNull('run_at');
    }

    public function markAsRun()
    {
        $this->update(['run_at' => now()]);
    }
}
