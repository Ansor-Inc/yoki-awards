<?php

namespace Modules\Book\Repositories;

use App\Models\Publisher;
use Modules\Book\Repositories\Interfaces\PublisherRepositoryInterface;

class PublisherRepository implements PublisherRepositoryInterface
{
    public function getAllPublishers($perPage = 0)
    {
        $query = Publisher::query()->select('id', 'title');

        return $perPage == 0 ? $query->get() : $query->paginate($perPage);
    }

    public function getPublisherBooks(Publisher $publisher, $perPage = 0)
    {
        $query = $publisher->books()->with('author:id,firstname,lastname')->onlyListingFields();

        return $perPage == 0 ? $query->limit(100)->get() : $query->paginate($perPage);
    }
}