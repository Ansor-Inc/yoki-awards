<?php

namespace Modules\Book\Repositories\Interfaces;

use App\Models\Genre;

interface GenreRepositoryInterface
{
    public function getGenres();

    public function getGenreBooks(Genre $genre, $perPage = 0);
}