<?php

namespace Modules\Comment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Comment\Interfaces\CommentRepositoryInterface;
use Modules\Comment\Repositories\CommentRepository;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Comment';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'comment';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }
}
