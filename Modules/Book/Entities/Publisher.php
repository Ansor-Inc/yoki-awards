<?php

namespace Modules\Book\Entities;

use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Publisher extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;

    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('logo');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
