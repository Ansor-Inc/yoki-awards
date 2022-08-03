<?php

namespace Modules\Book\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Book\Repositories\Interfaces\PublisherRepositoryInterface;
use Modules\Book\Repositories\PublisherRepository;
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
}
