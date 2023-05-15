<?php

namespace Modules\Blog\Entities;

use App\Traits\HasTagsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Blog\Enums\ArticleStatus;
use Modules\Blog\Filters\ArticleFilter;
use Modules\Comment\Entities\Comment;
use Modules\Reaction\Traits\LikeAndDislikeable;

/**
 * @property mixed $tags
 * @property mixed $id
 * @property mixed $user_id
 * @property mixed $user_type
 */
class Article extends Model
{
    use LikeAndDislikeable;
    use HasTagsTrait;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::addGlobalScope('published', fn($query) => $query->where('status', ArticleStatus::PUBLISHED->value));
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
