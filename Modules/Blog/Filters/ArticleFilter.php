<?php

namespace Modules\Blog\Filters;

use App\AbstractFilter;

class ArticleFilter extends AbstractFilter
{
    public function popular(): void
    {
        $this->query->orderBy('views', 'DESC');
    }

    public function tag($tag): void
    {
        $this->query->whereHas('tags', fn($query) => $query->where('name', $tag));
    }

    public function limit($limit): void
    {
        $this->query->limit($limit);
    }
}
