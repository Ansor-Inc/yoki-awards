<?php

namespace Modules\Book\Repositories\Interfaces;

use Modules\Book\Entities\Genre;

interface GenreRepositoryInterface
{
    public function getGenres();

    public function getGenreBooks(Genre $genre, $perPage = 0);
}