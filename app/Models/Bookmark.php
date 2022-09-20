<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'book_user_statuses';
    protected $fillable = ['user_id', 'book_id', 'bookmarked'];
}
