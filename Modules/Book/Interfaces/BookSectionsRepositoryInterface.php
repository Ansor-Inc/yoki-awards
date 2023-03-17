<?php

namespace Modules\Book\Interfaces;

interface BookSectionsRepositoryInterface
{
    public function getTrendingBooks();

    public function getTrendingAudioBooks();

    public function getSpecialBooks();

    public function getAcademicsBooks();

    public function getBooksBySections();

}
