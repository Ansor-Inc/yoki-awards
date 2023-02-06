<?php

namespace Modules\Book\Entities\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Stevebauman\Location\Facades\Location;

class ShowPaidBookOnlyToLocalUsersScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        if ($position = Location::get()) {
            if (!in_array($position->countryCode, config('app.paid_books_available_countries'))) {
                $builder->where('is_free', true);
            }
        }
    }
}
