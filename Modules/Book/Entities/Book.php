<?php

namespace Modules\Book\Entities;

use App\Models\Tag;
use App\Traits\HasMediaCollectionsTrait;
use App\Traits\HasTagsTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Modules\Book\Entities\Scopes\NonCommercialBooksOnlyForDesktop;
use Modules\Book\Entities\Scopes\OnlyApprovedBooksScope;
use Modules\Book\Entities\Scopes\ShowPaidBookOnlyToLocalUsersScope;
use Modules\Book\Entities\Traits\InteractsWithBookFiles;
use Modules\Book\Filters\BookFilter;
use Modules\Book\Helpers\BookRatingPercentage;
use Modules\Comment\Entities\Comment;
use Modules\User\Entities\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Book extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use InteractsWithBookFiles;
    use HasRecursiveRelationships;
    use HasTagsTrait;

    public const BOOK_URL_EXPIRATION = 10;

    protected static function booted(): void
    {
        static::addGlobalScope(new OnlyApprovedBooksScope());
        static::addGlobalScope(new ShowPaidBookOnlyToLocalUsersScope());
        static::addGlobalScope(new NonCommercialBooksOnlyForDesktop());
    }

    // Relationships:
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function readers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, BookRead::class);
    }

    public function bookUserStatuses(): HasMany
    {
        return $this->hasMany(BookUserStatus::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class)->whereNotNull('rating');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    //Scopes:
    public function scopeFilter($query, array $filters): void
    {
        (new BookFilter($query))->apply($filters);
    }

    public function scopeOnlyListingFields($query): void
    {
        $query->select(['id', 'title', 'author_id', 'is_free', 'book_type', 'price']);
    }

    //Helper methods:
    public function currentUserStatus(): HasOne
    {
        return $this->hasOne(BookUserStatus::class)
            ->where('user_id', auth()->id())
            ->withDefault(['bookmarked' => false, 'rating' => null]);
    }

    public function percentagePerRating(): Collection
    {
        return $this->ratings()
            ->selectRaw("rating, ROUND((COUNT(rating)/?)*100) as percentage", [$this->ratings()->count()])
            ->groupBy('rating')
            ->get();
    }

    public function isBoughtBy(Authenticatable|User $user)
    {
        return $user->purchases()->completed()->ofBook($this)->exists();
    }

    //Attributes:
    public function getPercentagePerRatingAttribute(): array
    {
        return BookRatingPercentage::makeFrom($this->percentagePerRating());
    }

    public function getDescriptionExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->description), 120);
    }

    //Spatie media-library media collections (https://spatie.be/docs/laravel-medialibrary/v10/introduction)
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useFallbackUrl(asset('media/missingbook.png'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('image_optimized');
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl('image');
    }
}
