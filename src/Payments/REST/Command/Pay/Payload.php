<?php

namespace App\Payments\REST\Command\Pay;

use App\Payments\Model\Entity\Coupon;
use App\Payments\Model\Entity\Product;
use App\Payments\Model\PaymentProcessor\PaymentProcessorTypeEnum;
use App\Payments\Validation\TaxNumber\PaymentTaxNumber;
use App\Shared\Validation\EntityExists\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class Payload
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(1)]
        #[EntityExists(
            entityClass: Product::class,
            searchField: 'id'
        )]
        public readonly int $productId,
        #[Assert\NotBlank]
        #[PaymentTaxNumber]
        public readonly string $taxNumber,
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [PaymentProcessorTypeEnum::class, 'getValues'])]
        public readonly string $paymentProcessor,
        #[Assert\NotBlank(allowNull: true)]
        #[EntityExists(
            entityClass: Coupon::class,
            searchField: 'code'
        )]
        public readonly ?string $couponCode = null,
    ) {
    }

    /**
     * Implemented to not use #[Assert\Type] attribute on $paymentProcessor field.
     */
    public function getPaymentProcessorType(): PaymentProcessorTypeEnum
    {
        return PaymentProcessorTypeEnum::from($this->paymentProcessor);
    }
}
