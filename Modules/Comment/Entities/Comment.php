<?php

namespace Modules\Comment\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\User\Entities\User;
use Modules\Reaction\Traits\LikeAndDislikeable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Comment extends Model
{
    use HasRecursiveRelationships;
    use LikeAndDislikeable;

    protected $guarded = ['id'];

    public function getParentKeyName(): string
    {
        return 'reply_id';
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'reply_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFilter(Builder $builder, array $filters)
    {
        //Todo comment filters
    }
}
