<?php

namespace Modules\Book\Entities;

use App\Models\Comment;
use App\Models\Tag;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Billing\Entities\Purchase;
use Modules\Book\Enums\BookStatus;
use Modules\Book\Filters\BookFilter;
use Modules\User\Entities\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Book extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use HasRecursiveRelationships;

    protected static function booted()
    {
        //always retrieve only approved books
        static::addGlobalScope('available', fn($query) => $query->where('status', BookStatus::APPROVED->value));
    }

    public function scopeFilter($query, array $filters)
    {
        (new BookFilter($query))->apply($filters);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('image_optimized')
            ->height(224)
            ->width(165)
            ->optimize();
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

    public function getBookFileUrl()
    {
        if ($this->is_free) {
            return $this->getFirstMediaUrl('book_file');
        }

        if ($this->isBoughtByCurrentUser()) {
            $url = $this->getFirstMediaPath('book_file');
            return $url !== '' ? Storage::temporaryUrl($this->getFirstMediaPath('book_file'), now()->addMinutes(5)) : null;
        }

        return null;
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }


    public function getAudioFileUrls()
    {
        return collect($this->getMedia('audio_files')->map(fn($media) => $media->getUrl()))->toArray();
    }

    public function isBoughtByCurrentUser()
    {
        return true;
    }

    public function getFragmentAttribute()
    {
        return $this->getFirstMediaUrl('fragment');
    }
}
