<?php

namespace Modules\Book\Entities;

use App\Traits\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Genre extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function getIconAttribute(): string
    {
        return $this->getFirstMediaUrl('icon');
    }

    public function getIconActiveAttribute(): string
    {
        return $this->getFirstMediaUrl('icon_active');
    }
}
