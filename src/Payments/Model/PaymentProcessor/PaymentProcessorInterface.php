<?php

namespace App\Payments\Model\PaymentProcessor;

use Symfony\Component\DependencyInjection\Attribute as DIC;

#[DIC\AutoconfigureTag('app.payments.model.payment_processor')]
interface PaymentProcessorInterface
{
    /**
     * @see PaymentProcessorTypeEnum
     */
    public static function getName(): string;

    /**
     * @param int $price in cents
     *
     * @throws \RuntimeException on errors or unsuccessfully operation
     */
    public function processPayment(int $price): void;
}
