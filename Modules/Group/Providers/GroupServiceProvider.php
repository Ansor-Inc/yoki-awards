<?php

namespace Modules\Group\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Group\Repositories\BlackListRepository;
use Modules\Group\Repositories\GroupAdminRepository;
use Modules\Group\Repositories\GroupRepository;
use Modules\Group\Repositories\MembershipRepository;
use Modules\GroupInterfaces\Interfaces\BlackListRepositoryInterface;
use Modules\GroupInterfaces\Interfaces\GroupAdminRepositoryInterface;
use Modules\GroupInterfaces\Interfaces\GroupRepositoryInterface;
use Modules\GroupInterfaces\Interfaces\MembershipRepositoryInterface;

class GroupServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Group';

    protected string $moduleNameLower = 'group';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(MembershipRepositoryInterface::class, MembershipRepository::class);
        $this->app->bind(GroupAdminRepositoryInterface::class, GroupAdminRepository::class);
        $this->app->bind(BlackListRepositoryInterface::class, BlackListRepository::class);
    }

    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
