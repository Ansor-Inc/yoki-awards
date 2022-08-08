<?php

namespace Modules\Book\Repositories\Interfaces;

interface BookRepositoryInterface
{
    public function getBooks(array $filters);

    public function getBookById(int $id);

    public function getSimilarBooks(int $id);

    public function searchBooks(string $search);
}