<?php

namespace App\Payments\Model\PaymentProcessor;

use App\Shared\Logger\ExceptionHelper;
use Psr\Log\LoggerInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

readonly class StripeAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private StripePaymentProcessor $paymentProcessor,
        private LoggerInterface $logger
    ) {
    }

    public static function getName(): string
    {
        return PaymentProcessorTypeEnum::STRIPE->name;
    }

    public function processPayment(int $price): void
    {
        try {
            $success = $this->paymentProcessor->processPayment($price);
        } catch (\Throwable $e) {
        } finally {
            if (!isset($success) || !$success) {
                $e ??= null;

                $this->logger->error(
                    'Error occurred during payment process',
                    [
                        'exception' => $e ? ExceptionHelper::serializeMainData($e) : null,
                        'processor' => $this->getName(),
                    ]
                );

                throw new \RuntimeException('Error occurred during payment process');
            }
        }
    }
}
