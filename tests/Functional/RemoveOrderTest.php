<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RemoveOrderTest extends WebTestCase
{
    public function testRemoveOrder(): void
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/order/remove/2',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        self::assertResponseIsSuccessful();
    }

    public function testRemoveNotExistingOrder(): void
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/order/remove/256',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        self::assertResponseStatusCodeSame(404);
    }
}
