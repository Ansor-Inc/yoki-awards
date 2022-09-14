<?php

namespace Modules\Book\Repositories;

use App\Models\Publisher;
use Modules\Book\Repositories\Interfaces\PublisherRepositoryInterface;

class PublisherRepository implements PublisherRepositoryInterface
{
    public function getPublisherById(int $id)
    {
        return Publisher::query()->findOrFail($id);
    }

    public function getAllPublishers()
    {
        return Publisher::all();
    }

    public function getPublisherBooks(Publisher $publisher, $perPage = 0)
    {
        $query = $publisher->books()->with('author:id,firstname,lastname')->onlyListingFields();

        if ($perPage != 0) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}