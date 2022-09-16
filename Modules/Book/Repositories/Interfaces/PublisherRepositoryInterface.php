<?php

namespace Modules\Book\Repositories\Interfaces;

use App\Models\Publisher;

interface PublisherRepositoryInterface
{
    public function getAllPublishers($perPage = 0);

    public function getPublisherBooks(Publisher $publisher, $perPage = 0);
}