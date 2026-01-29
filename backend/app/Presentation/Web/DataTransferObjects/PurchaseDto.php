<?php

namespace App\Presentation\Web\DataTransferObjects;

final class PurchaseDto
{
    public function __construct(
        public int $customerId,
        public float $amount,
        public string $purchaseDate,
        public ?string $description = null,
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
            purchaseDate: $data['purchase_date'],
            description: $data['description'] ?? null,
        );
    }
}
