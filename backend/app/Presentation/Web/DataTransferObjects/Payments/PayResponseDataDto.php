<?php

namespace App\Presentation\Web\DataTransferObjects\Payments;

readonly class PayResponseDataDto
{
    public function __construct(
        public string  $checkoutUrl,
        public ?string $reference = null,
        public ?string $accessCode = null,
    ){}

    /**
     * Create the object from an array
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            checkoutUrl: $data['checkout_url'],
            reference: $data['reference'] ?? null,
            accessCode: $data['access_code'] ?? null,
        );
    }
}
