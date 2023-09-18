<?php

namespace App\Payments\Model\PaymentProcessor;

enum PaymentProcessorTypeEnum: string
{
    case PAYPAL = 'PAYPAL';
    case STRIPE = 'STRIPE';

    /**
     * @return non-empty-string[]
     */
    public static function getValues(): array
    {
        return array_map(
            static fn (self $enum) => $enum->value,
            self::cases()
        );
    }
}
