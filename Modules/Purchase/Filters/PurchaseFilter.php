<?php

namespace Modules\Purchase\Filters;

use App\AbstractFilter;

class PurchaseFilter extends AbstractFilter
{
    public function type($type)
    {
        $this->query->whereRelation('book', 'type', $type);
    }
}