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

    public function fetchMenus(string $apiEndpoint, string $apiHost): array
    {
        $menusArray = [];
        $response = $this->client->request('GET', $apiEndpoint, [
            'headers' => [
                'Host' => $apiHost
            ]
        ]);

        if ($this->responseIsValid($response)) {
            foreach (json_decode($response->getContent(), true)['hydra:member'] as $menuArray) {
                /** @var Menu $menu */
                $menu = $this->serializer->deserialize($this->serializer->serialize($menuArray, 'json'), Menu::class, 'json');
                $itemsArray = $this->fetchMenuItems($apiEndpoint, $apiHost, $menu->getId());
                /** @var MenuItem $item */
                foreach ($itemsArray as $item) {
                    $menu->addItem($item);
                }

                $menusArray[] = $menu;
            }
        }

        return $menusArray;
    }

    public function fetchMenuItems(string $apiEndpoint, string $apiHost, int $menuId): array
    {
        $menuItemsArray = [];
        $response = $this->client->request('GET', $apiEndpoint . "/$menuId", [
            'headers' => [
                'Host' => $apiHost
            ]
        ]);

        if ($this->responseIsValid($response)) {
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
}