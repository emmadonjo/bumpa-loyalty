<?php

namespace App\Domains\Store\Services;

use App\Domains\Store\Enums\PurchaseStatus;
use App\Domains\Store\Persistence\Contracts\PurchaseRepositoryInterface;
use App\Domains\Store\Persistence\Entities\Purchase;
use App\Domains\Store\Services\Payment\PaymentGateWayFactory;
use App\Infrastructure\Messaging\Contracts\MessageProducerInterface;
use App\Infrastructure\Messaging\Events\AchievementUnlocked;
use App\Presentation\Web\DataTransferObjects\Payments\TransactionDto;
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
            'currency' => $dto->currency,
            'reference' => $dto->reference,
            'provider' => $dto->provider,
            'request_payload' => $dto->requestPayload,
        ];

        return $this->repository->save($payload);
    }

    public function findByReference(string $reference): ?Purchase
    {
        return $this->repository->findByReference($reference);
    }

    public function verify(Purchase $purchase): TransactionDto
    {
        $paymentGateway = PaymentGateWayFactory::make($purchase->provider);
       return $paymentGateway->verify($purchase->reference);
    }

    /**Updates a record with provided attributes
     * @param Purchase $purchase
     * @param array $attributes
     * @return Purchase
     */
    public function update(Purchase $purchase, array $attributes): Purchase
    {
        $purchase->update($attributes);
        return $purchase;
    }

    /**
     * Publish achievement unlocked to message broker
     * @param Purchase $purchase
     * @return void
     */
    public function fireAchievementUnlocked(Purchase $purchase): void
    {
        $this->messageProducer->publish(AchievementUnlocked::class, [
            'customer_id' => $purchase->user_id,
            'amount' => $purchase->amount,
            'reference' => $purchase->reference,
            'purchase_date' => $purchase->purchased_at,
            'currency' => $purchase->currency,
            'description' => $purchase->description,
            'provider' => $purchase->provider,
        ]);
    }

    /**
     * Cancel a purchase if pending
     * @param string $reference
     * @return bool
     */
    public function cancelPurchase(string $reference): bool
    {
        $purchase = $this->findByReference($reference);
        if (!$purchase) {
            return false;
        }

        if ($purchase->status !== PurchaseStatus::PENDING){
            return false;
        }

        $this->update($purchase, [
            'status' => PurchaseStatus::Cancelled
        ]);
        return true;
    }
}
