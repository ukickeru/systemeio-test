<?php

namespace App\Payments\Model\Tax;

enum TaxRateProviderTypeEnum: string
{
    case DE = 'DE';
    case FR = 'FR';
    case GR = 'GR';
    case IT = 'IT';
}
