<?php

namespace App\Payments\Model\Tax;

class GreeceTaxRateProvider implements TaxRateProviderInterface
{
    use TaxProviderTrait;

    public static function getName(): string
    {
        return TaxRateProviderTypeEnum::GR->name;
    }

    public function getTaxRate(): int
    {
        return 24;
    }

    private function getTaxNumberPattern(): string
    {
        return '/GR\d{9,9}/';
    }
}
