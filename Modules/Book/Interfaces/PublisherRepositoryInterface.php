<?php

namespace Modules\Book\Interfaces;

use Modules\Book\Entities\Publisher;

interface PublisherRepositoryInterface
{
    public function getAllPublishers($perPage = 0);

    public function getPublisherBooks(Publisher $publisher, $perPage = 0);
}
