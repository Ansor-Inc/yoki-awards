<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Blog\Interfaces\ArticleCommentRepositoryInterface;
use Modules\Blog\Interfaces\ArticleRepositoryInterface;
use Modules\Blog\Repositories\ArticleCommentRepository;
use Modules\Blog\Repositories\ArticleRepository;

class BlogServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Blog';

    protected string $moduleNameLower = 'blog';

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(ArticleCommentRepositoryInterface::class, ArticleCommentRepository::class);
    }

    public function provides(): array
    {
        return [];
    }
}
