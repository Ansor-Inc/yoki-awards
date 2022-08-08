<?php

namespace Modules\Book\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Book\Http\Requests\PublisherBooksRequest;
use Modules\Book\Repositories\Interfaces\BookRepositoryInterface;
use Modules\Book\Repositories\Interfaces\PublisherRepositoryInterface;
use Modules\Book\Transformers\BookResource;
use Modules\Book\Transformers\PublisherResource;

class PublisherController extends Controller
{
    protected PublisherRepositoryInterface $repository;
    protected BookRepositoryInterface $bookRepository;

    public function __construct(PublisherRepositoryInterface $repository, BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->repository = $repository;
    }

    public function index()
    {
        $publishers = $this->repository->getAllPublishers();

        return PublisherResource::collection($publishers);
    }

    public function show(int $id)
    {
        $publisher = $this->repository->getPublisherById($id);

        return PublisherResource::make($publisher);
    }

    public function publisherBooks(int $id, PublisherBooksRequest $request)
    {
        $publisher = $this->repository->getPublisherById($id);

        $filters = array_merge(
            $request->validated(),
            ['publisher' => $publisher->id],
        );

        $books = $this->bookRepository->getBooks($filters);

        return BookResource::collection($books);
    }
}
