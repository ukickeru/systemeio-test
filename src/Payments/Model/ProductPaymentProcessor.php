<?php

namespace App\Payments\Model;

use App\Payments\Model\Entity\Coupon;
use App\Payments\Model\Entity\Product;
use App\Payments\Model\PaymentProcessor\PaymentProcessorProvider;
use App\Payments\Model\PaymentProcessor\PaymentProcessorTypeEnum;

readonly class ProductPaymentProcessor
{
    public function __construct(
        private ProductPriceCalculator $productPriceCalculator,
        private PaymentProcessorProvider $paymentProcessorProvider
    ) {
    }

    /**
     * @throws \RuntimeException
     */
    public function processPayment(
        Product $product,
        string $taxNumber,
        PaymentProcessorTypeEnum $paymentProcessor,
        Coupon $coupon = null,
    ): void {
        $price = $this->productPriceCalculator->calculateTotalPrice($product, $taxNumber, $coupon);
        $paymentProcessor = $this->paymentProcessorProvider->getByType($paymentProcessor);

        $paymentProcessor->processPayment($price);
    }
}
