<?php

namespace App\Application\Providers;

use App\Domains\Accounts\Persistence\Contracts\UserRepositoryInterface;
use App\Domains\Accounts\Persistence\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {

    }
}
