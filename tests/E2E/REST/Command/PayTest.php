<?php

namespace App\Tests\E2E\REST\Command;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayTest extends WebTestCase
{
    private UrlGeneratorInterface $urlGenerator;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = $this->createClient();
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->getContainer()->get(UrlGeneratorInterface::class);
        $this->urlGenerator = $urlGenerator;
    }

    public function testSuccessfulExecution(): void
    {
        $this->client->jsonRequest(
            'POST',
            $this->urlGenerator->generate('payments_pay', [], UrlGeneratorInterface::ABSOLUTE_URL),
            [
                'productId' => 1,
                'taxNumber' => 'DE123456789',
                'couponCode' => 'PER1',
                'paymentProcessor' => 'PAYPAL',
            ]
        );

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json', $response);
        $this->assertNotEmpty($response->getContent());

        $parsedResponse = json_decode($response->getContent(), true, flags: JSON_THROW_ON_ERROR);

        $this->assertIsArray($parsedResponse);
        $this->assertEmpty($parsedResponse);
    }
}
