<?php

namespace App\Application\Providers;

use App\Domains\Store\Persistence\Contracts\PurchaseRepositoryInterface;
use App\Domains\Store\Persistence\Repositories\PurchaseRepository;
use Illuminate\Support\ServiceProvider;

class StoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PurchaseRepositoryInterface::class, PurchaseRepository::class);
    }

    public function boot(): void
    {

    }
}
