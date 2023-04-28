<?php

namespace Modules\Content\Entities;

use App\Traits\HasMediaCollectionsTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Banner extends Model implements HasMedia
{
    use HasMediaCollectionsTrait;
}
