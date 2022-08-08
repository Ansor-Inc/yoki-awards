<?php

namespace Modules\Book\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Book\Http\Requests\BookIndexRequest;
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

    public function show(int $id)
    {
        $book = $this->repository->getBookById($id);

        return BookResource::make($book);
    }
}
