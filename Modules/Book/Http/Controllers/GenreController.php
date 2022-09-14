<?php

namespace Modules\Book\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Book\Transformers\BookResource;
use Modules\Book\Transformers\GenreResource;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::query()->select('id', 'title')->get();

        return GenreResource::collection($genres);
    }

    public function genreBooks(Genre $genre, Request $request)
    {
        $query = $genre->books()->onlyListingFields()->with('author:firstname,lastname');

        if ($request->has('per_page')) {
            $books = $query->paginate($request->input('per_page'));
        } else {
            $books = $query->limit(100)->get();
        }

        return BookResource::collection($books);
    }

}
