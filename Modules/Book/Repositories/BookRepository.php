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
            ->find($id)
            ->setAppends(['fragment', 'book_file']);
    }

    public function getSimilarBooks(int $id)
    {
        $book = $this->getBookById($id);

        return Book::query()->where('genre_id', $book->genre_id)
            ->onlyListingFields()
            ->get();
    }
}