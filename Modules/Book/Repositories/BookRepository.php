<?php

namespace Modules\Book\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Book\Entities\Book;
use Modules\Book\Entities\Bookmark;
use Modules\Book\Entities\BookRead;
use Modules\Book\Entities\Rating;
use Modules\Book\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getBookById(int $id)
    {
        return $this->makeSingleBookQueryFrom(Book::query())->findOrFail($id);
    }

    public function getBookWithVariants(int $id)
    {
        $book = Book::query()->select('id', 'title')->findOrFail($id);

        return $this->makeSingleBookQueryFrom($book->bloodline())->get();
    }

    protected function makeSingleBookQueryFrom($query)
    {
        return $query->select(['id', 'title', 'description', 'language', 'page_count', 'publication_date', 'price', 'compare_price', 'is_free', 'book_type', 'publisher_id', 'genre_id', 'author_id', 'voice_director', 'shop_link'])
            ->withAvg('ratings as rating', 'rating')
            ->withCount('ratings as vote_count')
            ->with(['author:id,firstname,lastname,about,copyright', 'publisher:id,title', 'genre:id,title', 'tags:name', 'currentUserStatus']);
    }

    public function markAsCompleted(int $bookId, int $userId): Model|Builder
    {
        return BookRead::query()->firstOrCreate([
            'book_id' => $bookId,
            'user_id' => $userId
        ]);
    }

    public function rateTheBook(int $bookId, int $userId, int $value): Model|Builder
    {
        $rating = Rating::query()->firstOrCreate(['user_id' => $userId, 'book_id' => $bookId]);
        $rating->update(['rating' => $value]);
        return $rating;
    }

    public function toggleBookmark(int $bookId, int $userId): Model|Builder
    {
        $bookmark = Bookmark::query()->firstOrCreate(['user_id' => $userId, 'book_id' => $bookId]);
        $bookmark->update(['bookmarked' => !$bookmark->bookmarked]);
        return $bookmark;
    }
}
