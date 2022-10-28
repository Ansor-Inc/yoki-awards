<?php

namespace Modules\Book\Entities\Traits;

use Illuminate\Support\Facades\Storage;

trait InteractsWithBookFiles
{
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

    public function getAudioFileUrls()
    {
        return collect($this->getMedia('audio_files'))->map(fn($media) => $media->getUrl())->toArray();
    }

    public function isBoughtByCurrentUser()
    {
        return true;
    }
}