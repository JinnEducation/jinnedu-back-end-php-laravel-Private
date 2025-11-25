<?php

namespace App\Services\Payment;

use InvalidArgumentException;

class PaymentManager
{
    public static function driver(string $driver): PaymentInterface
    {
        return match ($driver) {
            'paypal' => new PayPalService(),
            'stripe' => new StripeService(),
            'local-test' => new LocalTestService(),
            default => throw new InvalidArgumentException("Unsupported payment driver: $driver"),
        };
    }
}
