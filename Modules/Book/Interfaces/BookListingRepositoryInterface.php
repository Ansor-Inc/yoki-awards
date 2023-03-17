<?php

namespace Modules\Book\Interfaces;

use Modules\Book\Entities\Book;

interface BookListingRepositoryInterface
{
    public function getBooks(array $filters);

    public function getSimilarBooks(Book $book, int|null $limit);

    public function getSavedBooks(int|null $perPage);

    public function search(string $query);

}
