<?php

namespace App\Presentation\Web\DataTransferObjects;

final class PurchaseDto
{
    public function __construct(
        public int $customerId,
        public float $amount,
        public string $reference,
        public string $provider,
        public string $purchaseDate,
        public string $currency = 'NGN',
        public ?string $description = null,
        public array $requestPayload = [],
    ){}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            customerId: $data['customer_id'],
            amount: $data['amount'],
            reference: $data['reference'],
            provider: $data['provider'],
            purchaseDate: $data['purchase_date'],
            currency: $data['currency'],
            description: $data['description'] ?? null,
            requestPayload: $data['request_payload'] ?? [],
        );
    }
}
