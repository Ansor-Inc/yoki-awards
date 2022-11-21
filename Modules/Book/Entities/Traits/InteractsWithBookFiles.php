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

        if (auth()->check() && $this->isBoughtBy(auth()->user())) {
            return $this->getTemporaryUrl($this->getFirstMediaPath('book_file'));
        }

        return null;
    }

    public function getAudioFileUrls(): array
    {
        if ($this->is_free) {
            return collect($this->getMedia('audio_files'))->map(fn($media) => $media->getUrl())->toArray();
        }

        if (auth()->check() && $this->isBoughtBy(auth()->user())) {
            collect($this->getMedia('audio_files'))->map(fn($media) => $this->getTemporaryUrl($media->getUrl())->toArray());
        }

        return [];
    }

    public function getTemporaryUrl(string $url): ?string
    {
        return ($url !== '')
            ? Storage::temporaryUrl($url, now()->addMinutes(self::BOOK_URL_EXPIRATION))
            : null;
    }
}