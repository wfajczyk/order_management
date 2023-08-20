<?php


declare(strict_types=1);

namespace App\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderRecreateTest extends WebTestCase
{
    public function testRecreateOrder(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/order/recreate/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        self::assertResponseIsSuccessful();
        self::assertJson($client->getResponse()->getContent());
        self::assertNotEquals(1, $client->getResponse()->getContent());
    }

    public function testRecreateOrderWithNotExisting(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/order/recreate/168',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        self::assertResponseStatusCodeSame(404);
    }
}

