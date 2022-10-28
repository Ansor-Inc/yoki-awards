<?php

namespace Modules\Book\Entities;

use App\Models\Comment;
use App\Models\Tag;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Book\Entities\Traits\InteractsWithBookFiles;
use Modules\Book\Enums\BookStatus;
use Modules\Book\Filters\BookFilter;
use Modules\Book\Helpers\BookRatingPercentage;
use Modules\User\Entities\User;
use Spatie\MediaLibrary\HasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Book extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use InteractsWithBookFiles;
    use HasRecursiveRelationships;

    protected static function booted()
    {
        static::addGlobalScope('available', fn($query) => $query->where('status', BookStatus::APPROVED->value));//always retrieve only approved books
    }

    // Relationships:
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

    public function ratings()
    {
        return $this->hasMany(Rating::class)->whereNotNull('rating');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    //Scopes:
    public function scopeFilter($query, array $filters)
    {
        (new BookFilter($query))->apply($filters);
    }

    public function scopeOnlyListingFields($query)
    {
        $query->select(['id', 'title', 'author_id', 'is_free', 'book_type']);
    }

    //Helper methods:
    public function currentUserStatus()
    {
        return $this->hasOne(BookUserStatus::class)
            ->where('user_id', auth()->id())
            ->withDefault(['bookmarked' => false, 'rating' => null]);
    }

    public function percentagePerRating()
    {
        return $this->ratings()
            ->selectRaw("rating, ROUND((COUNT(rating)/?)*100) as percentage", [$this->ratings()->count()])
            ->groupBy('rating')
            ->get();
    }

    //Attributes:
    public function getPercentagePerRatingAttribute()
    {
        return BookRatingPercentage::makeFrom($this->percentagePerRating());
    }

    public function getDescriptionExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 120);
    }

    //Spatie media-library media collections (https://spatie.be/docs/laravel-medialibrary/v10/introduction)
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useFallbackUrl(asset('media/missingbook.png'));
    }
}
