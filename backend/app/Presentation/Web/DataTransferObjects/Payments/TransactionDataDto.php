<?php

namespace App\Presentation\Web\DataTransferObjects\Payments;

use App\Domains\Store\Enums\PurchaseStatus;

readonly class TransactionDataDto
{
    public function __construct(
        public string         $id,
        public PurchaseStatus $status,
        public string         $reference,
        public float          $amount,
        public float          $fees,
        public string         $channel,
        public array          $responsePayload,
        public string         $currency = 'NGN',
        public array          $metadata = [],
        public ?string        $paid_at = null,
    ){}

    /**
     * create a new object from array
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: PurchaseStatus::from($data['status']),
            reference: $data['reference'],
            amount: $data['amount'],
            fees: $data['fees'] ?? 0,
            channel: $data['channel'],
            responsePayload: $data['response_payload'] ?? [],
            currency: $data['currency'] ?? 'NGN',
            metadata: $data['metadata'] ?? [],
            paid_at: $data['paid_at'],
        );
    }
}
