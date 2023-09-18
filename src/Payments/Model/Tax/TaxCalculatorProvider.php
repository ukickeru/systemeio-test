<?php

namespace App\Payments\Model\Tax;

readonly class TaxCalculatorProvider
{
    public function __construct(private TaxRateChainProvider $taxChainProvider)
    {
    }

    /**
     * @return \Closure(float $productPrice): float
     *
     * @throws \RuntimeException
     */
    public function getByTaxNumber(string $taxNumber): \Closure
    {
        $taxRate = $this->taxChainProvider->getByTaxNumber($taxNumber)->getTaxRate();

        return static fn (float $productPrice) => $productPrice + (($productPrice / 100) * $taxRate);
    }
}
