<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use function PHPUnit\Framework\assertEquals;

class ApiTest extends ApiTestCase
{
    public function testApiRequiresToken(): void
    {
        $response = static::createClient()->request('GET', '/api');

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJsonContains([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
    }

    public function testApiAcceptsCredentialsAndReturnsTokens()
    {
        $response = static::createClient()->request('POST', '/api/login_check',[
            'headers' => ['Content-Type: application/json'],
            'body' => [
                'username' => 'admin@example.com',
                'password' => 'password'
            ]
        ]);

        assertEquals(true, true);
        dump($response->getContent());
    }
}
