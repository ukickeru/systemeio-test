<?php

namespace App\Payments\Model\Tax;

class ItalyTaxRateProvider implements TaxRateProviderInterface
{
    use TaxProviderTrait;

    public static function getName(): string
    {
        return TaxRateProviderTypeEnum::IT->name;
    }

    public function getTaxRate(): int
    {
        return 22;
    }

    private function getTaxNumberPattern(): string
    {
        return '/IT\d{11,11}/';
    }
}
