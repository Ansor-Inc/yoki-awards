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
            ->with('author:id,firstname,lastname');

        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    public function getBookById(int $id)
    {
        return Book::query()
            ->with(['author:id,firstname,lastname,about,copyright', 'publisher:id,title', 'genre:id,title', 'tags:name'])
            ->findOrFail($id)
            ->setAppends(['book_variant_ids']);
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