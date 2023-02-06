<?php

namespace Modules\Book\Entities\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Modules\Book\Enums\BookStatus;

class OnlyApprovedBooksScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', BookStatus::APPROVED->value);
    }
}
