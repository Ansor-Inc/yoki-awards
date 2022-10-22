<?php

namespace Modules\Book\Entities;

use App\Models\Comment;
use App\Models\Tag;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Book\Enums\BookStatus;
use Modules\Book\Filters\BookFilter;
use Modules\User\Entities\User;
use Spatie\MediaLibrary\HasMedia;

class Book extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;

    protected static function booted()
    {
        //always retrieve only approved books
        static::addGlobalScope('available', fn($query) => $query->where('status', BookStatus::APPROVED->value));
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

    public function readers()
    {
        return $this->hasManyThrough(User::class, BookRead::class);
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
        return $this->getFirstMediaUrl('image', 'image_optimized');
    }

    public function getFragmentAttribute()
    {
        return $this->getFirstMediaUrl('fragment');
    }
}
