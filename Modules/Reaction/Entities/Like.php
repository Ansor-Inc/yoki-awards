<?php

namespace Modules\Reaction\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\User\Entities\User;

class Like extends Model
{
    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        self::saving(function ($like) {
            $like->user_id = $like->user_id ?: auth()->id();
        });
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function liker(): BelongsTo
    {
        return $this->user();
    }

    public function scopeWithType(Builder $query, string $type): Builder
    {
        return $query->where('likeable_type', app($type)->getMorphClass());
    }
}
