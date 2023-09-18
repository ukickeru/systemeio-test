<?php

namespace App\Payments\Model\Tax;

use Symfony\Component\DependencyInjection\Attribute as DIC;

readonly class TaxRateChainProvider
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

    /**
     * @throws \RuntimeException
     */
    public function getByTaxNumber(string $taxNumber): TaxRateProviderInterface
    {
        foreach ($this->taxProviders as $taxProvider) {
            if ($taxProvider->supports($taxNumber)) {
                return $taxProvider;
            }
        }

        throw new \RuntimeException(sprintf('There are no suitable TaxProvider for "%s" tax number', $taxNumber));
    }
}
