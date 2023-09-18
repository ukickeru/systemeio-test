<?php

namespace App\Tests\Payments\Integration\Model\Tax;

use App\Payments\Model\Tax\TaxCalculatorProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxCalculatorProviderTest extends KernelTestCase
{
    private static TaxCalculatorProvider $taxCalculatorProvider;

    public static function setUpBeforeClass(): void
    {
        /* @phpstan-ignore-next-line "Static property does not accept object" */
        self::$taxCalculatorProvider = self::getContainer()->get(TaxCalculatorProvider::class);
    }

    /**
     * @dataProvider taxNumbersAndPricesDataProvider
     */
    public function testTaxCalculations(string $taxNumber, float $productPrice, float $resultPrice): void
    {
        $calculator = self::$taxCalculatorProvider->getByTaxNumber($taxNumber);

        $this->assertEquals($resultPrice, $calculator($productPrice));
    }

    /**
     * @return iterable<array{taxNumber: string, productPrice: float, resultPrice: float}>
     */
    public function taxNumbersAndPricesDataProvider(): iterable
    {
        yield ['taxNumber' => 'DE123456789', 'productPrice' => 100, 'resultPrice' => 119];
        yield ['taxNumber' => 'FRAA123456789', 'productPrice' => 100, 'resultPrice' => 120];
        yield ['taxNumber' => 'GR123456789', 'productPrice' => 100, 'resultPrice' => 124];
        yield ['taxNumber' => 'IT12345678901', 'productPrice' => 100, 'resultPrice' => 122];
    }
}
