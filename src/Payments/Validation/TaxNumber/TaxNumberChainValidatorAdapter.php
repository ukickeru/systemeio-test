<?php

namespace App\Payments\Validation\TaxNumber;

use App\Payments\Model\Tax\TaxRateProviderInterface;
use Symfony\Component\DependencyInjection\Attribute as DIC;

readonly class TaxNumberChainValidatorAdapter
{
    /**
     * @param iterable<TaxRateProviderInterface> $taxProviders
     */
    public function __construct(
        #[DIC\TaggedIterator(
            tag: 'app.payments.model.tax_provider',
            defaultIndexMethod: 'getName'
        )]
        private iterable $taxProviders
    ) {
    }

    public function isValid(string $taxNumber): bool
    {
        foreach ($this->taxProviders as $taxProvider) {
            if ($taxProvider->supports($taxNumber)) {
                return true;
            }
        }

        return false;
    }
}
