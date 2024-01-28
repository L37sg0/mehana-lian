<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use function PHPUnit\Framework\assertEquals;

class ApiTest extends ApiTestCase
{
    public function testApiNotAvailableWithoutAuthorization(): void
    {
        $response = static::createClient()->request('GET', '/api');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testApiAuthorizationReturnsAccessToken()
    {
        $clientId = $_ENV['CLIENT_ID'];
        $clientSecret = $_ENV['CLIENT_SECRET'];
        $authApiKey = base64_encode($clientId . ':' . $clientSecret);

        $apiEndpoint = $_ENV['API_ENDPOINT'];
        $apiHost = $_ENV['API_HOST'];

        $response = static::createClient()->request('POST', $apiEndpoint . '/authorize', [
            'headers' => [
                'Host' => $apiHost,
                'X-AUTH-API-KEY' => $authApiKey
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseContentKeys = array_keys(json_decode($response->getContent(), true));

        $this->assertEquals(['access_token', 'iat', 'exp', 'scopes'], $responseContentKeys);
    }

    public function testApiResourceCanBeAccessedWithToken()
    {

        $clientId = $_ENV['CLIENT_ID'];
        $clientSecret = $_ENV['CLIENT_SECRET'];
        $authApiKey = base64_encode($clientId . ':' . $clientSecret);

        $apiEndpoint = $_ENV['API_ENDPOINT'];
        $apiHost = $_ENV['API_HOST'];

        $tokenResponse = static::createClient()->request('POST', $apiEndpoint . '/authorize', [
            'headers' => [
                'Host' => $apiHost,
                'X-AUTH-API-KEY' => $authApiKey
            ]
        ]);
        $accessToken = json_decode($tokenResponse->getContent(), true)['access_token'];

        $resourceResponse = static::createClient()->request('GET', $apiEndpoint . '/menus', [
            'headers' => [
                'Host' => $apiHost,
                'Authorization' => "Bearer " . $accessToken
            ]
        ]);
        $this->assertEquals(200, $resourceResponse->getStatusCode());
    }

}
