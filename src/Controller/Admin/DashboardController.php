<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Menu;
use App\Entity\Message;
use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        /** @var AdminUrlGenerator $routeBuilder */
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ImageCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mehana Lian');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Back to Website', 'fas fa-home', 'home');
        yield MenuItem::linkToCrud('Images', 'fas fa-images', Image::class);
        yield MenuItem::linkToCrud('Reviews', 'fas fa-comments', Review::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-message', Message::class);
        yield MenuItem::linkToCrud('Menus', 'fas fa-utensils', Menu::class);
        yield MenuItem::linkToCrud('Plates', 'fas fa-plate-wheat', \App\Entity\MenuItem::class);
    }
    
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $userMenu = parent::configureUserMenu($user);
        $customMenuItems = [
            MenuItem::linkToRoute('Enable 2FA', 'fa-qrcode', 'app_2fa_enable')
        ];
        $userMenu->addMenuItems($customMenuItems);
        
        return $userMenu;
    }
}
