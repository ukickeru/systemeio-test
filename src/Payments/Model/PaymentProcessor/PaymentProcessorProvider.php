<?php

namespace App\Payments\Model\PaymentProcessor;

use Symfony\Component\DependencyInjection\Attribute as DIC;

readonly class PaymentProcessorProvider
{
    /**
     * @var array<string, PaymentProcessorInterface>
     */
    private array $paymentProcessors;

    /**
     * @param iterable<string, PaymentProcessorInterface> $paymentProcessors
     */
    public function __construct(
        #[DIC\TaggedIterator(
            tag: 'app.payments.model.payment_processor',
            defaultIndexMethod: 'getName'
        )]
        iterable $paymentProcessors
    ) {
        $mappedPaymentProcessors = [];

        foreach ($paymentProcessors as $id => $paymentProcessor) {
            $mappedPaymentProcessors[$id] = $paymentProcessor;
        }

        $this->paymentProcessors = $mappedPaymentProcessors;
    }

    /**
     * @throws \RuntimeException
     */
    public function getByType(PaymentProcessorTypeEnum $type): PaymentProcessorInterface
    {
        if (!isset($this->paymentProcessors[$type->name])) {
            throw new \RuntimeException(sprintf('There are no "%s" payment processor', $type->name));
        }

        return $this->paymentProcessors[$type->name];
    }
}
