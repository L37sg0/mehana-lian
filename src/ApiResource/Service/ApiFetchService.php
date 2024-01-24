<?php

namespace App\ApiResource\Service;

use App\ApiResource\Model\Menu;
use App\ApiResource\Model\MenuItem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiFetchService
{
    private Serializer $serializer;

    public function __construct(
        private HttpClientInterface $client,
    ) {

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param string $apiEndpoint
     * @param string $apiHost
     * @return array<Menu>
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchMenus(
        string $apiEndpoint,
        string $apiHost,
        string $apiClientId,
        string $apiClientSecret
    ): array
    {
        $authApiKey = $this->getApiAuthKey($apiClientId, $apiClientSecret);
        $tokenResponse = $this->getAccessToken($authApiKey, $apiEndpoint, $apiHost);
        $accessToken = json_decode($tokenResponse->getContent(), true)['access_token'];

        $menusArray = [];
        $response = $this->client->request('GET', $apiEndpoint . '/menus', [
            'headers' => [
                'Host' => $apiHost,
                'Authorization' => "Bearer $accessToken"
            ]
        ]);
//dd($response);
        if ($this->responseIsValid($response)) {
            /** @phpstan-ignore-next-line  */
            $responseMenus = json_decode($response->getContent(), true)['hydra:member'];
            /** @phpstan-ignore-next-line */
            foreach ($responseMenus as $menuArray) {
                /** @var Menu $menu */
                $menu = $this->serializer->deserialize($this->serializer->serialize($menuArray, 'json'), Menu::class, 'json');
                $itemsArray = $this->fetchMenuItems($apiEndpoint, $apiHost, $menu->getId(), $accessToken);
                /** @var MenuItem $item */
                foreach ($itemsArray as $item) {
                    $menu->addItem($item);
                }

                $menusArray[] = $menu;
            }
        }
//dd($menusArray);
        return $menusArray;
    }

    /**
     * @param string $apiEndpoint
     * @param string $apiHost
     * @param int $menuId
     * @return array<MenuItem>
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchMenuItems(string $apiEndpoint, string $apiHost, int $menuId, string $accessToken): array
    {
        $menuItemsArray = [];
        $response = $this->client->request('GET', $apiEndpoint . "/menus/$menuId", [
            'headers' => [
                'Host' => $apiHost,
                'Authorization' => "Bearer $accessToken"
            ]
        ]);

        if ($this->responseIsValid($response)) {
            /** @phpstan-ignore-next-line */
            foreach (json_decode($response->getContent(), true)['menuItems'] as $menuItemArray) {
                $menuItem = $this->serializer->deserialize(
                    $this->serializer->serialize($menuItemArray, 'json'),
                    MenuItem::class,
                    'json'
                );
                $menuItemsArray[] = $menuItem;
            }
        }

        return $menuItemsArray;
    }

    public function responseIsValid(ResponseInterface $response): bool
    {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299;
    }

    public function getAccessToken(string $authApiKey, string $apiEndpoint, string $apiHost): ResponseInterface
    {
        return $this->client->request('POST', $apiEndpoint . '/authorize', [
            'headers' => [
                'Host'           => $apiHost,
                'X-AUTH-API-KEY' => $authApiKey
            ]
        ]);
    }

    public function getApiAuthKey(string $apiClientId, string $apiClientSecret): string
    {
        return base64_encode("$apiClientId:$apiClientSecret");
    }
}