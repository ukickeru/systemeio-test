<?php

namespace App\Payments\Model\Tax;

class FranceTaxRateProvider implements TaxRateProviderInterface
{
    use TaxProviderTrait;

    public static function getName(): string
    {
        return TaxRateProviderTypeEnum::FR->name;
    }

    public function getTaxRate(): int
    {
        return 20;
    }

    private function getTaxNumberPattern(): string
    {
        return '/FR[a-zA-Z]{2,2}\d{9,9}/';
    }
}
