<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use function PHPUnit\Framework\assertEquals;

class ApiTest extends ApiTestCase
{
    public function testApiAvailable(): void
    {
        $response = static::createClient()->request('GET', '/api');

        $this->assertEquals(200, $response->getStatusCode());
    }

}
