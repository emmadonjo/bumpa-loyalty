<?php

namespace App\Infrastructure\Messaging\Consumers;

use App\Domains\Loyalty\Services\RewardsService;
use App\Infrastructure\Messaging\Events\AchievementUnlocked;
use App\Presentation\Web\DataTransferObjects\PurchaseDto;
use Illuminate\Contracts\Queue\ShouldQueue;

readonly class AchievementUnlockedConsumer implements ShouldQueue
{
    public function __construct(
        private RewardsService $rewardsService,
    ){}

    public function handle(AchievementUnlocked $event): void
    {
        logger("AchievementUnlocked consumer started", $event->payload);

        $this->rewardsService->handle(PurchaseDto::fromArray($event->payload));
    }
}
