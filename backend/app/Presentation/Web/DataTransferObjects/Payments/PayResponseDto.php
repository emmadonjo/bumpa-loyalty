<?php

namespace App\Presentation\Web\DataTransferObjects\Payments;

readonly class PayResponseDto
{
    public function __construct(
        public bool    $status,
        public PayResponseDataDto $data,
        public ?string $message = null,
    ){}

    /**
     * Create the object from an array
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'] ?? false,
            data: PayResponseDataDto::fromArray($data['data']),
            message: $data['message'] ?? null,
        );
    }
}
