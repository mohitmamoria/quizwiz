<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'body',
        'answers',
    ];

    protected $hidden = [
        'answers',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function previous(): ?Question
    {
        return $this->quiz->questions()->reorder()->orderBy('order', 'desc')
            ->where('order', '<', $this->order)->first();
    }

    public function next(): ?Question
    {
        return $this->quiz->questions()->where('order', '>', $this->order)->first();
    }
}
