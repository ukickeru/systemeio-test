<?php

namespace App\Payments\Service;

use App\Payments\Model\ProductPriceCalculator;
use App\Payments\Repository\CouponRepository;
use App\Payments\Repository\ProductRepository;

readonly class CalculateProductPrice
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private ProductPriceCalculator $productPriceCalculator
    ) {
    }

    /**
     * @return int product price in cents
     *
     * @throws \RuntimeException
     */
    public function execute(int $productId, string $taxNumber, string $couponCode = null): int
    {
        $product = $this->productRepository->findById($productId);
        $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);

        return $this->productPriceCalculator->calculateTotalPrice($product, $taxNumber, $coupon);
    }
}
