<?php

namespace Modules\Book\Repositories\Interfaces;

use App\Models\Publisher;

interface PublisherRepositoryInterface
{
    public function getPublisherById(int $id);

    public function getAllPublishers();

    public function getPublisherBooks(Publisher $publisher, $perPage = 0);
}