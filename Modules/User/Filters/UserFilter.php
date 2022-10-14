<?php

namespace Modules\User\Filters;

use App\AbstractFilter;

class UserFilter extends AbstractFilter
{
    public function search($searchString)
    {
        $this->query->where('fullname', 'LIKE', "%{$searchString}%");
    }
}