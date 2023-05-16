<?php

namespace Modules\Book\Entities\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Jenssegers\Agent\Facades\Agent;
use Modules\Book\Enums\BookType;

class NonCommercialBooksOnlyForDesktop implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if ($this->isRequestFromMobileDevice()) {
            $builder->whereNot('book_type', BookType::NON_COMMERCIAL->value);
        }
    }

    protected function isRequestFromMobileDevice(): bool
    {
        return str(request()->userAgent()->contains('Dart'))
            || Agent::isMobile();
    }
}
