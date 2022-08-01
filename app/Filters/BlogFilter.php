<?php

namespace App\Filters;

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
}