<?php

namespace Modules\Book\Repositories;

use App\Models\Publisher;
use Modules\Book\Repositories\Interfaces\PublisherRepositoryInterface;

class PublisherRepository implements PublisherRepositoryInterface
{

    public function getPublisherById(int $id)
    {

    }

    public function getAllPublishers()
    {
        return Publisher::all();
    }
}