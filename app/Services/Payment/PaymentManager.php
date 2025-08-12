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
            default => throw new InvalidArgumentException("Unsupported payment driver: $driver"),
        };
    }
}
