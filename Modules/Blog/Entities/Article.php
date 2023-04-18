<?php

namespace Modules\Blog\Entities;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Blog\Filters\BlogFilter;

class Article extends Model
{
    protected $guarded = ['id', 'published'];

    protected static function booted()
    {
        static::addGlobalScope('published', fn($query) => $query->where('published', true));
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeFilter($query, array $filters)
    {
        (new BlogFilter($query))->apply($filters);
    }
}
