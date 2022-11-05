<?php

namespace Modules\Group\Filters;

use App\AbstractFilter;

class GroupFilter extends AbstractFilter
{
    public function categoryId(int $id)
    {
        $this->query->where('group_category_id', $id);
    }
}