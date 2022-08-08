<?php

namespace App\Models;

use App\Traits\HasFilesTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Publisher extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
    use HasFilesTrait;

    public function getLogoAttribute()
    {
        return $this->getImageFromCollection('logo');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
