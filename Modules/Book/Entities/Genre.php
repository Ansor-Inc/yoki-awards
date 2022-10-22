<?php

namespace Modules\Book\Entities;

use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Genre extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getIconAttribute()
    {
        return $this->getFirstMediaUrl('icon');
    }

    public function getIconActiveAttribute()
    {
        return $this->getFirstMediaUrl('icon_active');
    }
}
