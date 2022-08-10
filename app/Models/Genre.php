<?php

namespace App\Models;

use App\Traits\HasFilesTrait;
use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
}
