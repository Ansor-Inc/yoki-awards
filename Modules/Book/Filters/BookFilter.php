<?php

namespace Modules\Book\Filters;

use App\AbstractFilter;

class BookFilter extends AbstractFilter
{
    public function trending()
    {
        $this->query->whereRelation('tags', 'name', 'trending');
    }

    public function type($bookType)
    {
        $this->query->where('book_type', $bookType);
    }

    public function free($isFree)
    {
        $this->query->where('is_free', $isFree);
    }
}