<?php

namespace App\Domains\Store\Services\Payment;

use App\Domains\Store\Services\Payment\Contracts\PaymentInterface;
use App\Domains\Store\Services\Payment\Providers\PaystackPaymentService;
use InvalidArgumentException;

class PaymentGateWayFactory
{
    public static function make(string $gateway = 'paystack'): PaymentInterface
    {
        return match ($gateway) {
            'paystack' => new PaystackPaymentService(),
            default => throw new InvalidArgumentException("Unknown payment gateway '$gateway' provided."),
        };
    }
}
