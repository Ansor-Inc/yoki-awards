<?php

namespace Modules\Book\Interfaces;

interface BookRepositoryInterface
{
    public function getBookById(int $id);

    public function getBookWithVariants(int $id);

    public function markAsCompleted(int $bookId, int $userId);

    public function rateTheBook(int $bookId, int $userId, int $value);

    public function toggleBookmark(int $bookId, int $userId);
}
