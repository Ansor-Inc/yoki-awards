<?php

namespace Modules\Book\Repositories\Interfaces;

interface PublisherRepositoryInterface
{
    public function getPublisherById(int $id);

    public function getAllPublishers();
}