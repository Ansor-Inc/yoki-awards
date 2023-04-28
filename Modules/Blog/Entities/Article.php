<?php

namespace Modules\Blog\Entities;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Blog\Filters\ArticleFilter;
use Modules\Comment\Entities\Comment;
use Modules\Reaction\Traits\LikeAndDislikeable;

class Article extends Model
{
    use LikeAndDislikeable;

    protected $guarded = ['id', 'published'];

    protected static function booted(): void
    {
        static::addGlobalScope('published', fn($query) => $query->where('published', true));
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFilter($query, array $filters): void
    {
        (new ArticleFilter($query))->apply($filters);
    }
}
