<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Interfaces\UserRepositoryInterface;
use Modules\User\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
