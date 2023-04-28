<?php

namespace Modules\Reaction\Providers;

use Illuminate\Support\ServiceProvider;

class ReactionServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Reaction';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'reaction';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
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
