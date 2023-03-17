<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class AbstractFilter
{
    public function __construct(protected Builder $query)
    {
    }

    public function apply(array $filters): void
    {
        foreach ($filters as $param => $value) {
            $methodName = Str::camel($param);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}($value);
            }
        }
    }
}
