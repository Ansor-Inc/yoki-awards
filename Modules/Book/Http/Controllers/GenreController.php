<?php

namespace Modules\Book\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Book\Entities\Genre;
use Modules\Book\Http\Requests\GetGenreBooksRequest;
use Modules\Book\Interfaces\GenreRepositoryInterface;
use Modules\Book\Transformers\BookListingResource;
use Modules\Book\Transformers\GenreResource;

class GenreController extends Controller
{
    protected GenreRepositoryInterface $genreRepository;

    public function __construct(GenreRepositoryInterface $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    public function index()
    {
        $genres = $this->genreRepository->getGenres();

        return GenreResource::collection($genres);
    }

    public function genreBooks(Genre $genre, GetGenreBooksRequest $request)
    {
        $books = $this->genreRepository->getGenreBooks($genre, $request->input('per_page'));

        return BookListingResource::collection($books);
    }
}
