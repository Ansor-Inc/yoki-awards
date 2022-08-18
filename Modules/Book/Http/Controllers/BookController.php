<?php

namespace Modules\Book\Http\Controllers;

use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Rating;
use Illuminate\Routing\Controller;
use Modules\Book\Http\Requests\BookIndexRequest;
use Modules\Book\Http\Requests\BookRatingRequest;
use Modules\Book\Repositories\BookSectionsRepository;
use Modules\Book\Repositories\Interfaces\BookRepositoryInterface;
use Modules\Book\Transformers\BookResource;

class BookController extends Controller
{
    protected BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(BookIndexRequest $request)
    {
        $books = $this->repository->getBooks($request->validated());

        return BookResource::collection($books);
    }

    public function sections(BookSectionsRepository $repository)
    {
        return $repository->getBooksBySections();
    }

    public function savedBooks()
    {
        return BookResource::collection($this->repository->getSavedBooks());
    }

    public function show($id)
    {
        $book = $this->repository->getBookById($id);
        $books = $this->repository->getBooksWithSimilarTitle($book->title);

        if ($books->count() > 1) {
            return BookResource::collection($books);
        }

        return BookResource::make($book);
    }

    public function bookmark(Book $book)
    {
        $user = request()->user();

        $status = Bookmark::toggle($user, $book);

        return response()->json([
            'bookmarked' => $status->bookmarked
        ]);
    }

    public function rate(Book $book, BookRatingRequest $request)
    {
        $user = request()->user();

        $status = Rating::rate($user, $book, $request->input('value'));

        return response()->json([
            'rating' => $status->rating
        ]);
    }
}
