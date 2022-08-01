<?php

namespace App\Models;

use App\Filters\BlogFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $with = ['tags'];

    protected static function booted()
    {
        static::addGlobalScope('published', function ($query) {
            $query->where('published', true);
        });
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
