<?php

namespace App\Domains\Store\Services\Payment\Contracts;

use App\Presentation\Web\DataTransferObjects\Payments\PayDto;
use App\Presentation\Web\DataTransferObjects\Payments\PayResponseDto;
use App\Presentation\Web\DataTransferObjects\Payments\TransactionDto;

interface PaymentInterface
{
    /**
     * Initiates payment to a given payment gateway
     * @param PayDto $payload
     * @return PayResponseDto
     */
    public function pay(PayDto $payload): PayResponseDto;

    public function verify(string $reference): TransactionDto;
}
