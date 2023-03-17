<?php

namespace Modules\Book\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Book\Entities\Publisher;
use Modules\Book\Http\Requests\GetPublisherBooksRequest;
use Modules\Book\Http\Requests\GetPublishersRequest;
use Modules\Book\Interfaces\PublisherRepositoryInterface;
use Modules\Book\Transformers\BookListingResource;
use Modules\Book\Transformers\PublisherResource;

class PublisherController extends Controller
{
    protected PublisherRepositoryInterface $repository;

    public function __construct(PublisherRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(GetPublishersRequest $request)
    {
        $publishers = $this->repository->getAllPublishers($request->input('per_page'));

        return PublisherResource::collection($publishers);
    }

    public function show(Publisher $publisher)
    {
        return PublisherResource::make($publisher);
    }

    public function getPublisherBooks(Publisher $publisher, GetPublisherBooksRequest $request)
    {
        $books = $this->repository->getPublisherBooks($publisher, $request->input('per_page'));

        return BookListingResource::collection($books);
    }
}
