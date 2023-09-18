<?php

namespace App\Payments\Service;

use App\Payments\Model\PaymentProcessor\PaymentProcessorTypeEnum;
use App\Payments\Model\ProductPaymentProcessor;
use App\Payments\Repository\CouponRepository;
use App\Payments\Repository\ProductRepository;

readonly class ProcessPayment
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private ProductPaymentProcessor $productPaymentProcessor
    ) {
    }

    /**
     * @throws \RuntimeException in case of error
     */
    public function execute(
        int $productId,
        string $taxNumber,
        PaymentProcessorTypeEnum $paymentProcessor,
        string $couponCode = null
    ): void {
        $product = $this->productRepository->findById($productId);
        $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);

        $this->productPaymentProcessor->processPayment($product, $taxNumber, $paymentProcessor, $coupon);
    }
}
