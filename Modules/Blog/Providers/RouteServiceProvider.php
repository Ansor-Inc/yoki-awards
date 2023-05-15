<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Blog\Entities\Article;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\Blog\Http\Controllers';

    public function boot(): void
    {
        parent::boot();

        Route::bind('articleWithoutScopes', function ($id) {
            return Article::query()->withoutGlobalScopes()->findOrFail($id);
        });
    }

    public function map(): void
    {
        $this->mapApiRoutes();
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Blog', '/Routes/api.php'));
    }
}
