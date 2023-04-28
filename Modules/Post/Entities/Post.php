<?php

namespace Modules\Post\Entities;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Comment\Entities\Comment;
use Modules\Group\Entities\Group;
use Modules\User\Entities\User;

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
        return $this->hasMany(PostLike::class)->where('liked', true);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
