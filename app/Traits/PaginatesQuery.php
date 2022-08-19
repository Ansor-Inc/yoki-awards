<?php

namespace App\Traits;

trait PaginatesQuery
{
    public function paginateConditionally($query, int|null $perPage)
    {
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

}