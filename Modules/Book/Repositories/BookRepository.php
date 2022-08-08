<?php

namespace Modules\Book\Repositories;

use App\Models\Book;
use Modules\Book\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    protected array $bookListFields = ['id', 'title', 'author_id', 'is_free', 'book_type'];

    public function getBooks(array $filters)
    {
        $query = Book::query()
            ->filter($filters)
            ->select($this->bookListFields)
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

        return Book::query()->where('genre_id', $book->genre_id)->select($this->bookListFields)->get();
    }

    public function searchBooks(string $search)
    {
        return Book::query()
            ->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->select('id', 'title', 'author_id', 'publisher_id')
            ->get();
    }

}