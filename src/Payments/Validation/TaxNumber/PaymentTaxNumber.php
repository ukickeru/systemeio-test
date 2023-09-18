<?php

namespace App\Payments\Validation\TaxNumber;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PaymentTaxNumber extends Constraint
{
    public string $message = 'Invalid tax number "{{ tax_number }}".';
}
