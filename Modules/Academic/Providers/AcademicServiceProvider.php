<?php

namespace Modules\Academic\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Academic\Interfaces\AcademicsRepositoryInterface;
use Modules\Academic\Repositories\AcademicsRepository;

class AcademicServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Academic';

    protected string $moduleNameLower = 'academic';

    public function boot(): void
    {
    }

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(AcademicsRepositoryInterface::class, AcademicsRepository::class);
    }

    public function provides(): array
    {
        return [];
    }
}
