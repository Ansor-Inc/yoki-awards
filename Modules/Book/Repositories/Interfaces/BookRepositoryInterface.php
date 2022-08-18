<?php

namespace Modules\Book\Repositories\Interfaces;

use App\Models\User;

interface BookRepositoryInterface
{
    public function getBooks(array $filters);

    public function getBookById(int $id);

    public function getSimilarBooks(int $id);

    public function getSavedBooks();

    public function getBooksWithSimilarTitle(string $title);
}