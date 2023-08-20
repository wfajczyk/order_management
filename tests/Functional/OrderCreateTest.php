<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderCreateTest extends WebTestCase
{

    public function testCreateOrder(): void
    {
        $client = static::createClient();

        $data = [];

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
    }
}
