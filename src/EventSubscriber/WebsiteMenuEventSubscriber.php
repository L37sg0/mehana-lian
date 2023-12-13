<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class WebsiteMenuEventSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private Environment $twig,
        private RouterInterface $router,
    ) {
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->twig->addGlobal('websiteMenuItems', $this->getWebsiteMenuItems());
    }

    /**
     * @return string[]
     */
    public function getWebsiteMenuItems(): array
    {
        $websiteMenuItems = [];
        $routes = array_filter(
            $this->router->getRouteCollection()->all(),
            function ($route) {
                return $route->hasDefault('includeInWebsiteMenu') && $route->getDefault('includeInWebsiteMenu') === true;
            }
        );

        foreach ($routes as $name => $route) {
            $websiteMenuItems[$name] = $route->getPath();
        }

        return $websiteMenuItems;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent'
        ];
    }
}