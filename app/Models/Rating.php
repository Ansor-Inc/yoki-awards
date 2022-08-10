<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'book_user_statuses';

    protected $fillable = ['user_id', 'book_id', 'rating'];

    public static function rate(User $user, Book $book, int $rating)
    {
        $status = self::query()->firstOrCreate(['user_id' => $user->id, 'book_id' => $book->id]);

        $status->update([
            'rating' => $rating
        ]);

        return $status;
    }
}
