<?php

namespace App\Payments\REST\Query\CalculatePayment;

use App\Payments\Service\CalculateProductPrice;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
readonly class Controller
{
    public function __construct(private CalculateProductPrice $calculateProductPrice)
    {
    }

    #[Route(
        path: '/calculate-price',
        name: 'calculate_price',
        host: '%app.domain%',
        methods: ['POST'],
        format: 'json'
    )]
    public function __invoke(#[MapRequestPayload] Payload $payload): JsonResponse
    {
        // In cents
        $price = $this->calculateProductPrice->execute(
            $payload->productId,
            $payload->taxNumber,
            $payload->couponCode
        );

        return new JsonResponse(['price' => round($price / 100, 2)]);
    }
}
