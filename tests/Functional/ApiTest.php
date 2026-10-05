<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ApiTest extends WebTestCase
{
    public function testListStatusesEndpointIsAvailable(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/statuses');

        self::assertResponseIsSuccessful();
        self::assertResponseFormatSame('json');
    }

    public function testInvalidJsonReturnsBadRequest(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], '{');

        self::assertResponseStatusCodeSame(400);
        self::assertResponseFormatSame('json');
    }
}
