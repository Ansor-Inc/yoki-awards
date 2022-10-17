<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Book\Entities\Book;
use Modules\Book\Events\UserReadBooksCountChanged;
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

    public function getBookWithVariants(int $bookId)
    {
        $this->repository->checkBookExistence($bookId);
        $books = $this->repository->getBookWithVariants($bookId);

        return BookResource::collection($books);
    }

    public function bookmark(int $bookId)
    {
        $this->repository->checkBookExistence($bookId);
        $status = $this->repository->toggleBookmark($bookId, auth()->id());

        return response()->json(['bookmarked' => $status->bookmarked]);
    }

    public function rate(int $bookId, UpdateBookRatingRequest $request)
    {
        $this->repository->checkBookExistence($bookId);
        $status = $this->repository->rateTheBook($bookId, auth()->id(), $request->input('value'));

        return response()->json(['rating' => $status->rating]);
    }

    public function markBookAsCompleted(int $bookId)
    {
        $this->repository->checkBookExistence($bookId);
        $marked = $this->repository->markAsCompleted($bookId, auth()->id());

        if (isset($marked)) {
            UserReadBooksCountChanged::dispatch(auth()->user());
            return response(['message' => 'Marked as completed!']);
        }

        return $this->failed();
    }
}
