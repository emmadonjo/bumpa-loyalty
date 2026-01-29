<?php

namespace App\Presentation\Web\DataTransferObjects\Payments;

readonly class TransactionDto
{
    public function __construct(
        public bool $status,
        public TransactionDataDto $data,
        public ?string $message = null,
    ){}

    /**
     * Create new object from array
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'],
            data: TransactionDataDto::fromArray($data['data']),
            message: $data['message'] ?? null,
        );
    }
}
