<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MagicCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($magicCode) {
            // 6-digit number as code
            $magicCode->code = Str::password(6, false, true, false, false);
            $magicCode->expires_at = now()->addMinutes(15);
        });
    }

    public function scopeUnexpired(Builder $builder): Builder
    {
        return $builder->whereTime('expires_at', '>', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
