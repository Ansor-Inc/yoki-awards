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
use Modules\Book\Interfaces\BookListingRepositoryInterface;
use Modules\Book\Interfaces\BookRepositoryInterface;
use Modules\Book\Repositories\BookSectionsRepository;
use Modules\Book\Transformers\BookListingResource;
use Modules\Book\Transformers\BookResource;

class BookController extends Controller
{
    public function index(GetBooksRequest $request, BookListingRepositoryInterface $repository)
    {
        $books = $repository->getBooks($request->validated());

        return BookListingResource::collection($books);
    }

    public function search(Request $request, BookListingRepositoryInterface $repository)
    {
        $request->validate(['query' => 'required|string']);
        $books = $repository->search($request->input('query'));

        return BookListingResource::collection($books);
    }

    public function getBooksBySections(BookSectionsRepository $repository)
    {
        return $repository->getBooksBySections();
    }

    public function getSavedBooks(GetSavedBooksRequest $request, BookListingRepositoryInterface $repository)
    {
        $books = $repository->getSavedBooks($request->input('per_page'));

        return BookListingResource::collection($books);
    }

    public function getSimilarBooks(Book $book, GetSimilarBooksRequest $request, BookListingRepositoryInterface $repository)
    {
        $books = $repository->getSimilarBooks($book, $request->input('limit'));

        return BookListingResource::collection($books);
    }

    public function show($id, BookRepositoryInterface $repository)
    {
        return BookResource::make($repository->getBookById($id));
    }

    public function getBookWithVariants(int $bookId, BookRepositoryInterface $repository)
    {
        $books = $repository->getBookWithVariants($bookId);

        return BookResource::collection($books);
    }

    public function bookmark(int $bookId, BookRepositoryInterface $repository)
    {
        $status = $repository->toggleBookmark($bookId, auth()->id());

        return response()->json(['bookmarked' => $status->bookmarked]);
    }

    public function rate(int $bookId, UpdateBookRatingRequest $request, BookRepositoryInterface $repository)
    {
        $status = $repository->rateTheBook($bookId, auth()->id(), $request->input('value'));

        return response()->json(['rating' => $status->rating]);
    }

    public function markBookAsCompleted(int $bookId, BookRepositoryInterface $repository)
    {
        $marked = $repository->markAsCompleted($bookId, auth()->id());

        if (isset($marked)) {
            UserReadBooksCountChanged::dispatch(auth()->user());
            return response(['message' => 'Marked as completed!']);
        }

        return $this->failed();
    }
}
