<?php

namespace Modules\Book\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

    public function show(int $id)
    {
        $publisher = $this->repository->getPublisherById($id);

        return PublisherResource::make($publisher);
    }

    public function publisherBooks(int $id, Request $request)
    {
        $publisher = $this->repository->getPublisherById($id);
        $books = $publisher->books()->paginate($request->input('limit'));

        return BookResource::collection($books);
    }
}
