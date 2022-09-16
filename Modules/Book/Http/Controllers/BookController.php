<?php

namespace Modules\Book\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Book\Http\Requests\GetBooksRequest;
use Modules\Book\Http\Requests\GetSavedBooksRequest;
use Modules\Book\Http\Requests\GetSimilarBooksRequest;
use Modules\Book\Http\Requests\UpdateBookRatingRequest;
use Modules\Book\Repositories\BookSectionsRepository;
use Modules\Book\Repositories\Interfaces\BookRepositoryInterface;
use Modules\Book\Transformers\BookListingResource;
use Modules\Book\Transformers\BookResource;

class BookController extends Controller
{
    protected BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(GetBooksRequest $request)
    {
        $books = $this->repository->getBooks($request->validated());

        return BookListingResource::collection($books);
    }

    public function search(Request $request)
    {
        $request->validate(['query' => 'required|string']);
        $books = $this->repository->search($request->input('query'));

        return BookListingResource::collection($books);
    }

    public function getBooksBySections(BookSectionsRepository $repository)
    {
        return $repository->getBooksBySections();
    }

    public function getSavedBooks(GetSavedBooksRequest $request)
    {
        $books = $this->repository->getSavedBooks($request->input('per_page'));

        return BookListingResource::collection($books);
    }

    public function getSimilarBooks(Book $book, GetSimilarBooksRequest $request)
    {
        $books = $this->repository->getSimilarBooks($book, $request->input('limit'));

        return BookListingResource::collection($books);
    }

    public function show($id)
    {
        return BookResource::make($this->repository->getBookById($id));
    }

    public function getBookWithVariants($id)
    {
        $books = $this->repository->getBookWithVariants((int)$id);

        return BookResource::collection($books);
    }

    public function bookmark(Book $book)
    {
        $user = auth()->user();
        $status = Bookmark::toggle($user, $book);

        return response()->json(['bookmarked' => $status->bookmarked]);
    }

    public function rate(Book $book, UpdateBookRatingRequest $request)
    {
        $user = auth()->user();
        $status = Rating::rate($user, $book, $request->input('value'));

        return response()->json(['rating' => $status->rating]);
    }
}
