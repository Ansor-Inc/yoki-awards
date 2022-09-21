<?php

namespace App\Models;

use App\Filters\BookFilter;
use App\Traits\HasFilesTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Book\Enums\BookStatus;
use Spatie\MediaLibrary\HasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Book extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use HasFilesTrait;

    protected static function booted()
    {
        static::addGlobalScope('available', function ($query) {
            $query->where('status', BookStatus::APPROVED->value);
        });
    }

    public function scopeFilter($query, array $filters)
    {
        (new BookFilter($query))->apply($filters);
    }

    public function scopeOnlyListingFields($query)
    {
        $query->select(['id', 'title', 'author_id', 'is_free', 'book_type']);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('reply_id');
    }

    public function bookUserStatuses()
    {
        return $this->hasMany(BookUserStatus::class);
    }

    public function currentUserStatus()
    {
        return $this->hasOne(BookUserStatus::class)->where('user_id', auth()->id())->withDefault([
            'bookmarked' => false,
            'rating' => null
        ]);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getImageAttribute()
    {
        return $this->getImageFromCollection('image');
    }

    public function getFragmentAttribute()
    {
        return $this->getFileFromCollection('fragment');
    }

    public function getBookFileAttribute()
    {
        return $this->getFileFromCollection('book_file');
    }
}
