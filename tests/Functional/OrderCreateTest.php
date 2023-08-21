<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderCreateTest extends WebTestCase
{

    public function testCreateOrder(): void
    {
        $client = static::createClient();

        $data = [
            [
                'id' => 5,
                'quantity' => 10,
            ],
            [
                'id' => 6,
                'quantity' => 11,
            ],
        ];

        $client->request(
            'POST',
            '/order/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data, JSON_THROW_ON_ERROR),
        );

        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());
        $order = $this->getOrder((int)$client->getResponse()->getContent());
        self::assertNotNull($order);
    }

    private function getOrder(int $id): ?Order
    {
        return self::getContainer()->get(OrderRepository::class)->find($id);
    }
}
