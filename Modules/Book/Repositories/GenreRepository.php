<?php

namespace Modules\Book\Repositories;

use Modules\Book\Entities\Genre;
use Modules\Book\Interfaces\GenreRepositoryInterface;

class GenreRepository implements GenreRepositoryInterface
{
    public function getGenres()
    {
        return Genre::query()->select('id', 'title')->get();
    }

    public function getGenreBooks(Genre $genre, $perPage = 0)
    {
        $query = $genre->books()
            ->onlyListingFields()
            ->with('author:id,firstname,lastname')
            ->withAvg('bookUserStatuses as rating', 'rating');

        return $perPage == 0 ? $query->limit(100)->get() : $query->paginate($perPage);
    }
}
