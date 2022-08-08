<?php

namespace App\Filters;

class BookFilter extends AbstractFilter
{
    public function trending()
    {
        //Todo: trending algorithm
    }

    public function limit($limit)
    {
        $this->query->limit($limit);
    }

    public function type($bookType)
    {
        $this->query->where('book_type', $bookType);
    }

    public function free($isFree)
    {
        $this->query->where('is_free', $isFree);
    }

    public function publisher($publisherId)
    {
        $this->query->where('publisher_id', $publisherId);
    }

    public function genre($genreId)
    {
        $this->query->where('genreId', $genreId);
    }
}