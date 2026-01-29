<?php

namespace App\Application\Providers;

use App\Infrastructure\Messaging\Consumers\AchievementUnlockedConsumer;
use App\Infrastructure\Messaging\Consumers\BadgeUnlockedConsumer;
use App\Infrastructure\Messaging\Contracts\MessageProducerInterface;
use App\Infrastructure\Messaging\Events\AchievementUnlocked;
use App\Infrastructure\Messaging\Events\BadgeUnlocked;
use App\Infrastructure\Messaging\Producers\MemoryMessageProducer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MessageProducerInterface::class, MemoryMessageProducer::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // bind events to their consumers/listeners

        Event::listen(
            AchievementUnlocked::class,
            [AchievementUnlockedConsumer::class, 'handle'],
        );

        Event::listen(
            BadgeUnlocked::class,
            [BadgeUnlockedConsumer::class, 'handle'],
        );

        /**
         * Prevent lazy-loading of models in a
         * non-production environment
         */
        Model::shouldBeStrict(!App::isProduction());

        // Force https scheme in production for added security
        if (App::isProduction()) {
            URL::forceScheme('https');
        }
    }
}
