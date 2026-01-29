<?php

namespace App\Domains\Store\Services;

use App\Domains\Store\Persistence\Contracts\PurchaseRepositoryInterface;
use App\Domains\Store\Persistence\Entities\Purchase;
use App\Infrastructure\Messaging\Contracts\MessageProducerInterface;
use App\Infrastructure\Messaging\Events\AchievementUnlocked;
use App\Presentation\Web\DataTransferObjects\PurchaseDto;

readonly class PurchaseService
{
    public function __construct(
        private PurchaseRepositoryInterface $repository,
        private MessageProducerInterface    $messageProducer,
    ){}

    public function purchase(PurchaseDto $dto): Purchase
    {
        $payload = [
            'user_id' => $dto->customerId,
            'amount' => $dto->amount,
            'purchased_at' => $dto->purchaseDate,
            'description' => $dto->description,
        ];

        $purchase = $this->repository->save($payload);
        $this->messageProducer->publish(AchievementUnlocked::class, $payload);

        return $purchase;
    }
}
