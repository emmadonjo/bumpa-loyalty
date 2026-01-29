<?php

namespace App\Infrastructure\Messaging\Consumers;

use App\Infrastructure\Messaging\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;

class BadgeUnlockedConsumer implements ShouldQueue
{
    public function handle(BadgeUnlocked $event): void
    {
        logger("AchievementUnlocked consumer started", $event->payload);

        // Nothing is implemented here as no scenario was required
        // However, in a real application, this might be notification on any channel - mail, database, push, etc.
    }
}
