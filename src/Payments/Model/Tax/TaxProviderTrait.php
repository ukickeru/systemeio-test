<?php

namespace App\Payments\Model\Tax;

trait TaxProviderTrait
{
    public function supports(string $taxNumber): bool
    {
        return (bool) preg_match($this->getTaxNumberPattern(), $taxNumber);
    }

    abstract protected function getTaxNumberPattern(): string;
}
