<?php

namespace App\Models;

use Brackets\Media\HasMedia\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

class Publisher extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;

    public function getLogoAttribute()
    {
        $mediaItems = $this->getMedia('logo');

        if (isset($mediaItems[0])) {
            return Str::replace(config('app.url'), config('app.admin_url'), $mediaItems[0]->getUrl());
        }

        return 'https://maxler.com/local/templates/maxler/assets/img/not-found.png';
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

}
