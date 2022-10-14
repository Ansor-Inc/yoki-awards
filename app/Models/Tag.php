<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Entities\Article;

class Tag extends Model
{
    use HasFactory;

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

}
