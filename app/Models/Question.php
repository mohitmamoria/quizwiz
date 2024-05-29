<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'order',
        'body',
        'answers',
    ];

    protected $hidden = [
        'answers',
    ];

    protected $appends = [
        'body_html',
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

    public function bodyHtml(): Attribute
    {
        return Attribute::make(get: fn () => Str::markdown($this->body));
    }

    public function correctAnswer(): Attribute
    {
        return Attribute::make(get: fn () => Str::title($this->answers[0]));
    }
}
