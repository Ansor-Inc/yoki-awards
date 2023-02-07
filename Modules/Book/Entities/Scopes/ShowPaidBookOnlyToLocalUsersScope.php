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
        $location = Location::get(request()->server('HTTP_DO_CONNECTING_IP'));
        $countryCode = $location ? $location->countryCode : false;

        if (!in_array($countryCode, config('app.paid_books_available_countries'))) {
            $builder->where('is_free', true);
        }
    }
}
