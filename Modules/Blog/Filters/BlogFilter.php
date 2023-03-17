<?php

namespace Modules\Blog\Filters;

use App\AbstractFilter;

class BlogFilter extends AbstractFilter
{
    public function popular()
    {
        $this->query->orderBy('views', 'DESC');
    }

    public function tag($tag)
    {
        $this->query->whereHas('tags', fn($query) => $query->where('name', $tag));
    }

    public function limit($limit)
    {
        $this->query->limit($limit);
    }
}
