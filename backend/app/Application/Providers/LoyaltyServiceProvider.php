<?php

namespace App\Application\Providers;

use App\Domains\Loyalty\Persistence\Contracts\UserAchievementRepositoryInterface;
use App\Domains\Loyalty\Persistence\Repositories\UserAchievementRepository;
use Illuminate\Support\ServiceProvider;

class LoyaltyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserAchievementRepositoryInterface::class,
            UserAchievementRepository::class
        );
    }

    public function boot(): void
    {

    }
}
