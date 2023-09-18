<?php

namespace App\Payments\REST\Command\Pay;

use App\Payments\Service\ProcessPayment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
readonly class Controller
{
    public function __construct(private ProcessPayment $processPayment)
    {
    }

    #[Route(
        path: '/pay',
        name: 'pay',
        host: '%app.domain%',
        methods: ['POST'],
        format: 'json'
    )]
    public function __invoke(#[MapRequestPayload] Payload $payload): JsonResponse
    {
        $this->processPayment->execute(
            $payload->productId,
            $payload->taxNumber,
            $payload->getPaymentProcessorType(),
            $payload->couponCode
        );

        return new JsonResponse();
    }
}
