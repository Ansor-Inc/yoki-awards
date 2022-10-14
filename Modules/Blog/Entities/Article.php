<?php

namespace Modules\Blog\Entities;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Filters\BlogFilter;

class Article extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('published', fn($query) => $query->where('published', true));
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopeFilter($query, array $filters)
    {
        (new BlogFilter($query))->apply($filters);
    }
}
