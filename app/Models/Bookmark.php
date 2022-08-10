<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'book_user_statuses';

    protected $fillable = ['user_id', 'book_id', 'bookmarked'];

    public static function toggle(User $user, Book $book)
    {
        $bookmark = self::query()->firstOrCreate(['user_id' => $user->id, 'book_id' => $book->id]);

        $bookmark->update([
            'bookmarked' => !$bookmark->bookmarked
        ]);

        return $bookmark;
    }
}
