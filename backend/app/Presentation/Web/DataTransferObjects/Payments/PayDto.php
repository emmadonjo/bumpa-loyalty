<?php

namespace App\Presentation\Web\DataTransferObjects\Payments;

class PayDto
{
    public function __construct(
        public float $amount,
        public string $email,
        public string $reference,
        public string $currency = 'NGN',
        public array $metaData = [],
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            email: $data['email'],
            reference: $data['reference'],
            currency: $data['currency'] ?? 'NGN',
            metaData: $data['metaData'] ?? [],
        );
    }
}
