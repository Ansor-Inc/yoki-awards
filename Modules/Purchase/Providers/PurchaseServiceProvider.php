<?php

namespace Modules\Purchase\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Repositories\TransactionRepository;

class PurchaseServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Purchase';

    protected string $moduleNameLower = 'purchase';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(PurchaseRepositoryInterface::class, PurchaseRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    public function provides(): array
    {
        return [];
    }
}
