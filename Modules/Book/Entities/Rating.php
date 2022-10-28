<?php

namespace Modules\Book\Entities;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'book_user_statuses';

    protected $fillable = ['user_id', 'book_id', 'rating'];
}
