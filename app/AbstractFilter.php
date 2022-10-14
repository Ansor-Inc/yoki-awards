<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function apply(array $filters)
    {
        foreach ($filters as $param => $value) {
            if (method_exists($this, $param)) {
                $this->{$param}($value);
            }
        }
    }
}