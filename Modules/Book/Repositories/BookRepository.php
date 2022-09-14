<?php

namespace Modules\Book\Repositories;

use App\Models\Book;
use Modules\Book\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getBooks(array $filters)
    {
        $query = Book::query()
            ->filter($filters)
            ->onlyListingFields()
            ->withAvg('bookUserStatuses as rating', 'rating')
            ->with('author:id,firstname,lastname');

        if (auth()->check()) {
            $query->with('currentUserStatus');
        }

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->limit(100)->get();
    }

    public function getBookById(int $id)
    {
        return Book::query()
            ->select(['id', 'title', 'description', 'language', 'page_count', 'publication_date', 'price', 'compare_price', 'is_free', 'book_type', 'publisher_id', 'genre_id', 'author_id'])
            ->withAvg('bookUserStatuses as rating', 'rating')
            ->with(['author:id,firstname,lastname,about,copyright', 'publisher:id,title', 'genre:id,title', 'tags:name'])
            ->findOrFail($id);
    }

    public function getSimilarBooks(int $id)
    {
        $book = $this->getBookById($id);

        return Book::query()->where('genre_id', $book->genre_id)
            ->onlyListingFields()
            ->get();
    }

    public function getSavedBooks()
    {
        return Book::query()
            ->onlyListingFields()
            ->whereHas('bookUserStatuses', function ($query) {
                $query->where('user_id', auth('sanctum')->user()->id)
                    ->where('bookmarked', true);
            })->get();
    }
}