<?php

namespace App\Tests\Payments\Unit\Model\Tax;

use App\Payments\Model\Tax\DeutscheTaxRateProvider;
use App\Payments\Model\Tax\FranceTaxRateProvider;
use App\Payments\Model\Tax\GreeceTaxRateProvider;
use App\Payments\Model\Tax\ItalyTaxRateProvider;
use App\Payments\Model\Tax\TaxRateProviderInterface;
use PHPUnit\Framework\TestCase;

class TaxProvidersTest extends TestCase
{
    /**
     * @var array<string, TaxRateProviderInterface>
     */
    private static array $taxProviders;

    public static function setUpBeforeClass(): void
    {
        self::$taxProviders = self::initTaxProviders();
    }

    public function testTaxValues(): void
    {
        $this->assertEquals($this->getProvider('de')->getTaxRate(), 19);
        $this->assertEquals($this->getProvider('fr')->getTaxRate(), 20);
        $this->assertEquals($this->getProvider('gr')->getTaxRate(), 24);
        $this->assertEquals($this->getProvider('it')->getTaxRate(), 22);
    }

    /**
     * @dataProvider taxNumbersProvidersDataProvider()
     */
    public function testTaxNumberSupport(string $provider, string $taxNumber, bool $supports): void
    {
        $this->assertEquals($this->getProvider($provider)->supports($taxNumber), $supports);
    }

    /**
     * @return iterable<array{provider: string, taxNumber: string, supports: bool}>
     */
    public function taxNumbersProvidersDataProvider(): iterable
    {
        // DE
        yield ['provider' => 'de', 'taxNumber' => 'DE123456789', 'supports' => true];
        yield ['provider' => 'de', 'taxNumber' => 'DE000000000', 'supports' => true];
        yield ['provider' => 'de', 'taxNumber' => 'DE999999999', 'supports' => true];
        yield ['provider' => 'de', 'taxNumber' => 'DE12345678_', 'supports' => false];
        yield ['provider' => 'de', 'taxNumber' => 'DE-12345678', 'supports' => false];
        yield ['provider' => 'de', 'taxNumber' => 'DE 12345678', 'supports' => false];
        yield ['provider' => 'de', 'taxNumber' => 'DE12345678', 'supports' => false];
        yield ['provider' => 'de', 'taxNumber' => '12345678901', 'supports' => false];
        // Other samples
        yield ['provider' => 'fr', 'taxNumber' => 'FRAA123456789', 'supports' => true];
        yield ['provider' => 'gr', 'taxNumber' => 'GR123456789', 'supports' => true];
        yield ['provider' => 'it', 'taxNumber' => 'IT12345678901', 'supports' => true];
    }

    private function getProvider(string $name): TaxRateProviderInterface
    {
        return self::$taxProviders[strtoupper($name)];
    }

    /**
     * @return TaxRateProviderInterface[]
     */
    private static function initTaxProviders(): array
    {
        /**
         * @var TaxRateProviderInterface[] $providers
         */
        $providers = [
            new DeutscheTaxRateProvider(),
            new FranceTaxRateProvider(),
            new GreeceTaxRateProvider(),
            new ItalyTaxRateProvider(),
        ];

        $remapped = [];

        foreach ($providers as $provider) {
            $remapped[$provider::getName()] = $provider;
        }

        return $remapped;
    }
}
