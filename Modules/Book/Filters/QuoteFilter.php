<?php

namespace Modules\Book\Filters;

use App\AbstractFilter;

class QuoteFilter extends AbstractFilter
{
    public function limit(int $limit)
    {
        $this->query->limit($limit);
    }
}
