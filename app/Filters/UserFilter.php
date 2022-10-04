<?php

namespace App\Filters;

class UserFilter extends AbstractFilter
{
    public function search($searchString)
    {
        $this->query->where('fullname', 'LIKE', "%{$searchString}%");
    }
}