<?php

namespace Modules\Book\Entities;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'book_user_statuses';
    protected $fillable = ['user_id', 'book_id', 'bookmarked'];
}
