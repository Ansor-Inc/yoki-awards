<?php

namespace Modules\Book\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Routing\Controller;
use Modules\Book\Http\Requests\GetPublisherBooksRequest;
use Modules\Book\Repositories\Interfaces\PublisherRepositoryInterface;
use Modules\Book\Transformers\BookResource;
use Modules\Book\Transformers\PublisherResource;

class PublisherController extends Controller
{
    protected PublisherRepositoryInterface $repository;

    public function __construct(PublisherRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $publishers = $this->repository->getAllPublishers();

        return PublisherResource::collection($publishers);
    }

    public function show(Publisher $publisher)
    {
        return PublisherResource::make($publisher);
    }

    public function getPublisherBooks(Publisher $publisher, GetPublisherBooksRequest $request)
    {
        $books = $this->repository->getPublisherBooks($publisher, $request->input('per_page'));

        return BookResource::collection($books);
    }
}
