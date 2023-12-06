<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'home', defaults: ['includeInWebsiteMenu' => true])]
    public function index(): Response
    {
        return $this->render('front/pages/home.html.twig');
    }

    #[Route('/about', name: 'about', defaults: ['includeInWebsiteMenu' => true])]
    public function about(): Response
    {
        return $this->render('front/pages/about.html.twig');
    }

    #[Route('/menu', name: 'menu', defaults: ['includeInWebsiteMenu' => true])]
    public function menu(): Response
    {
        return $this->render('front/pages/menu.html.twig');
    }

    #[Route('/events', name: 'events', defaults: ['includeInWebsiteMenu' => true])]
    public function events(): Response
    {
        return $this->render('front/pages/events.html.twig');
    }

    #[Route('/gallery', name: 'gallery', defaults: ['includeInWebsiteMenu' => true])]
    public function gallery(): Response
    {
        return $this->render('front/pages/gallery.html.twig');
    }

    #[Route('/contact', name: 'contact', defaults: ['includeInWebsiteMenu' => true])]
    public function contact(): Response
    {
        return $this->render('front/pages/contact.html.twig');
    }

    #[Route('/book-a-table', name: 'book-a-table', defaults: ['includeInWebsiteMenu' => false])]
    public function bookTable(): Response
    {
        return $this->render('front/pages/book-a-table.html.twig');
    }
}
