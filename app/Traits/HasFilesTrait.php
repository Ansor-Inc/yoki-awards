<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasFilesTrait
{
    public function getImageFromCollection(string $collectionName)
    {
        $mediaItems = $this->getMedia($collectionName);

        if (isset($mediaItems[0])) {
            return $this->getUrl($mediaItems[0]);
        }

        return asset('media/not-found.png');
    }

    public function getFileFromCollection(string $collectionName)
    {
        $media = $this->getMedia($collectionName);

        return isset($media[0]) ? $this->getUrl($media[0]) : null;
    }

    public function getUrl($mediaItem)
    {
        return Str::replace(config('app.url'), config('app.admin_url'), $mediaItem->getUrl());
    }
}