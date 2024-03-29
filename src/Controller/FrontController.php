<?php

namespace App\Controller;

use App\ApiResource\Service\ApiFetchService;
use App\Entity\Booking;
use App\Entity\Message;
use App\Entity\Review;
use App\Form\BookingType;
use App\Form\MessageType;
use App\Form\ReviewType;
use App\Repository\ImageRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use function Symfony\Component\Translation\t;

class FrontController extends AbstractController
{
    public function __construct(
        private NotifierInterface $notifier,
        private EntityManagerInterface $entityManager,
        private ImageRepository $imageRepository,
        private ReviewRepository $reviewRepository
    ) {
    }

    #[Route('/', name: 'home', defaults: ['includeInWebsiteMenu' => true])]
    public function index(): Response
    {
        $reviews = $this->reviewRepository->findAll();

        return $this->render('front/pages/home.html.twig', [
            'reviews' => $reviews
        ]);
    }

    #[Route('/about', name: 'about', defaults: ['includeInWebsiteMenu' => true])]
    public function about(): Response
    {
        return $this->render('front/pages/about.html.twig');
    }

    #[Route('/menu', name: 'menu', defaults: ['includeInWebsiteMenu' => true])]
    public function menu(
        ApiFetchService $fetchService,
        CacheInterface $cache
    ): Response {
        $apiEndpoint = $this->getParameter('api.endpoint');
        $apiHost = $this->getParameter('api.host');

        $apiClientId = $this->getParameter('api.client.id');
        $apiClientSecret = $this->getParameter('api.client.secret');

        // https://www.linkedin.com/learning/symfony-6-essential-training/cache?autoplay=true&resume=false
        $menus = $cache->get( 'menus', function( ItemInterface $item) use($fetchService, $apiEndpoint, $apiHost, $apiClientId, $apiClientSecret) {
            $item->expiresAfter(60);
            /** @phpstan-ignore-next-line  */
            return $fetchService->fetchMenus($apiEndpoint, $apiHost, $apiClientId, $apiClientSecret);
        });

        return $this->render('front/pages/menu.html.twig', [
            'menus' => $menus
        ]);
    }

    #[Route('/events', name: 'events', defaults: ['includeInWebsiteMenu' => true])]
    public function events(): Response
    {
        return $this->render('front/pages/events.html.twig');
    }

    #[Route('/gallery', name: 'gallery', defaults: ['includeInWebsiteMenu' => true])]
    public function gallery(): Response
    {
        $images = $this->imageRepository->findAll();
        return $this->render('front/pages/gallery.html.twig',[
            'images' => $images
        ]);
    }

    #[Route('/contact', name: 'contact', defaults: ['includeInWebsiteMenu' => true])]
    public function contact(
        Request $request,
    ): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->notifier->send(new Notification(
                'Thank you for your message. Our support will review your case and contact you back.',
                ['browser']
            ));

            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted()) {
            $this->notifier->send(new Notification(
                'Can you check your submission? There are some problems with it.',
                ['browser']
            ));
        }


        return $this->render('front/pages/contact.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/book-a-table', name: 'book-a-table', defaults: ['includeInWebsiteMenu' => false])]
    public function bookTable(Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            $this->notifier->send(new Notification(
                t('Thank your for your booking. Our manager will review and confirm it to you soon.'),
                ['browser']
            ));

            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted()) {
            $this->notifier->send(new Notification(
                'Can you check your submission? There are some problems with it.',
                ['browser']
            ));
        }
        return $this->render('front/pages/book-a-table.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/reviews', name: 'reviews', defaults: ['includeInWebsiteMenu' => false])]
    public function reviews(
        Request $request,
    ): Response {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review
                ->setApproved(false)
                ->setFrontVisible(false)
                ->setImage('/assets/img/testimonials/testimonials-1.jpg');

            $this->entityManager->persist($review);
            $this->entityManager->flush();

            $this->notifier->send(new Notification(
                'Thank you for your feedback. Your review will be published after moderation.',
                ['browser']
            ));
            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted()) {
            $this->notifier->send(new Notification(
                'Can you check your submission? There are some problems with it.',
                ['browser']
            ));
        }

        return $this->render('front/pages/reviews.html.twig', [
            'form' => $form,
            'reviews'   => $this->reviewRepository->findAll(),
        ]);
    }
}
