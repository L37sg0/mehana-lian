<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ApiTestCaseTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/api');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Entrypoint',
            '@id' => '/api',
            '@type' => 'Entrypoint',
            'menu' => '/api/menus',
        ]);
    }
}
