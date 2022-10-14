<?php

namespace Modules\Book\Entities;

use App\Traits\HasFilesTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Genre extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use HasFilesTrait;

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getIconAttribute()
    {
        return $this->getImageFromCollection('icon');
    }

    public function getIconActiveAttribute()
    {
        return $this->getImageFromCollection('icon_active');
    }
}
