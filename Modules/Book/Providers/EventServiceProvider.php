<?php

namespace Modules\Book\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Book\Events\UserReadBooksCountChanged;
use Modules\Book\Listeners\UpdateUserDegree;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserReadBooksCountChanged::class => [
            UpdateUserDegree::class
        ]
    ];
}