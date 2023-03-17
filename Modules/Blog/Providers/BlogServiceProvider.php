<?php

namespace Modules\Blog\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Blog\Interfaces\BlogRepositoryInterface;
use Modules\Blog\Repositories\BlogRepository;

class BlogServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Blog';

    protected string $moduleNameLower = 'blog';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
    }

    public function provides(): array
    {
        return [];
    }
}
