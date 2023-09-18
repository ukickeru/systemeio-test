<?php

namespace App\Payments\Model\Tax;

class DeutscheTaxRateProvider implements TaxRateProviderInterface
{
    use TaxProviderTrait;

    public static function getName(): string
    {
        return TaxRateProviderTypeEnum::DE->name;
    }

    public function getTaxRate(): int
    {
        return 19;
    }

    private function getTaxNumberPattern(): string
    {
        return '/DE\d{9,9}/';
    }
}
