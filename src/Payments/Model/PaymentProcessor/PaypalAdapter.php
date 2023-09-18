<?php

namespace App\Payments\Model\PaymentProcessor;

use App\Shared\Logger\ExceptionHelper;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

readonly class PaypalAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $paymentProcessor,
        private LoggerInterface $logger
    ) {
    }

    public static function getName(): string
    {
        return PaymentProcessorTypeEnum::PAYPAL->name;
    }

    public function processPayment(int $price): void
    {
        try {
            $this->paymentProcessor->pay($price);
        } catch (\Throwable $e) {
            $this->logger->error(
                'Error occurred during payment process',
                [
                    'exception' => ExceptionHelper::serializeMainData($e),
                    'processor' => $this->getName(),
                ]
            );

            throw new \RuntimeException('Error occurred during payment process');
        }
    }
}
