<?php

namespace App\Payments\Model\Tax;

use Symfony\Component\DependencyInjection\Attribute as DIC;

#[DIC\AutoconfigureTag('app.payments.model.tax_provider')]
interface TaxRateProviderInterface
{
    /**
     * @see TaxRateProviderTypeEnum
     */
    public static function getName(): string;

    public function supports(string $taxNumber): bool;

    public function getTaxRate(): int;
}
