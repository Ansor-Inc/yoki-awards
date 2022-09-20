<?php

namespace Modules\Book\Repositories\Interfaces;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function getBooks(array $filters);

    public function search(string $query);

    public function getBookById(int $id);

    public function getBookWithVariants(int $id);

    public function getSimilarBooks(Book $book, $limit = 0);

    public function getSavedBooks(int|null $perPage = 0);

    public function markAsCompleted(int $bookId, int $userId);

    public function rateTheBook(int $bookId, int $userId, int $value);

    public function toggleBookmark(int $bookId, int $userId);

    public function checkBookExistence(int $id);
}