<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('front/pages/home.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('front/pages/about.html.twig');
    }

    #[Route('/menu', name: 'menu')]
    public function menu(): Response
    {
        return $this->render('front/pages/menu.html.twig');
    }

    #[Route('/events', name: 'events')]
    public function events(): Response
    {
        return $this->render('front/pages/events.html.twig');
    }

    #[Route('/gallery', name: 'gallery')]
    public function gallery(): Response
    {
        return $this->render('front/pages/gallery.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('front/pages/contact.html.twig');
    }
}
