<?php

namespace Modules\Group\Entities;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\User\Entities\User;
use function auth;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['degree_scope' => 'array'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id');
    }

    public function getCanCurrentUserEditDeleteAttribute()
    {
        return;
    }

    public function scopeApplyCurrentUserDegreeScopeFilter(Builder $query)
    {
        //filter posts by degree_scope with currently authenticated user degree
        $query->whereJsonContains('degree_scope', auth()->user()->degree)
            //if currently authenticated user is owner of the post then ignore degree scope
            ->orWhere('user_id', auth()->id());
    }
}
