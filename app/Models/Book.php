<?php

namespace App\Models;

use App\Filters\BookFilter;
use App\Traits\HasFilesTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Book\Enums\BookStatus;
use Spatie\MediaLibrary\HasMedia;

class Book extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use HasFilesTrait;

    protected $fillable = [];

    protected static function booted()
    {
        parent::booted();

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
