<?php

namespace App\Controller\Admin;

use App\Entity\Block;
use App\Entity\Dish;
use App\Entity\Event;
use App\Entity\Image;
use App\Entity\Menu;
use App\Entity\Page;
use App\Entity\Post;
use App\Entity\Profile;
use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(PageCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mehana Lian');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Pages', 'fas fa book', Page::class);
        yield MenuItem::linkToCrud('Blocks', 'fas fa book', Block::class);
        yield MenuItem::linkToCrud('Posts', 'fas fa book', Post::class);
        yield MenuItem::linkToCrud('Images', 'fas fa book', Image::class);
        yield MenuItem::linkToCrud('Menus', 'fas fa book', Menu::class);
        yield MenuItem::linkToCrud('Dishes', 'fas fa book', Dish::class);
        yield MenuItem::linkToCrud('Events', 'fas fa book', Event::class);
        yield MenuItem::linkToCrud('Profiles', 'fas fa book', Profile::class);
        yield MenuItem::linkToCrud('Reviews', 'fas fa book', Review::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
