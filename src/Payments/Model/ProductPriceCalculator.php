<?php

namespace App\Payments\Model;

use App\Payments\Model\CouponVisitor\DiscountCalculationVisitorInterface;
use App\Payments\Model\Entity\Coupon;
use App\Payments\Model\Entity\Product;
use App\Payments\Model\Tax\TaxCalculatorProvider;

readonly class ProductPriceCalculator
{
    public function __construct(
        private TaxCalculatorProvider $taxCalculatorProvider,
        private DiscountCalculationVisitorInterface $couponVisitor
    ) {
    }

    /**
     * @return int product price in cents, more than 0
     *
     * @throws \RuntimeException
     */
    public function calculateTotalPrice(Product $product, string $taxNumber, Coupon $coupon = null): int
    {
        $taxCalculator = $this->taxCalculatorProvider->getByTaxNumber($taxNumber);
        $couponCalculator = $this->getCouponCalculationClosure($coupon);

        return (int) max(ceil($taxCalculator($couponCalculator($product->getPrice()))), 0);
    }

    private function getCouponCalculationClosure(Coupon $coupon = null): \Closure
    {
        if (null === $coupon) {
            return static fn (float $productPrice) => $productPrice;
        }

        return $coupon->acceptDiscountCalculationVisitor($this->couponVisitor);
    }
}
