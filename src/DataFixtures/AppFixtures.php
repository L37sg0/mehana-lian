<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadReviews($manager);
        $this->loadGalleryImages($manager);

        $manager->flush();
    }
    

    public function loadReviews(ObjectManager $manager): void
    {
        $review1 = new Review();
        $review1
            ->setApproved(true)
            ->setContent('
                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum
                suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.
                Maecen aliquam, risus at semper.'
            )->setEmail('s.g@example-mail.com')
            ->setName('Saul Goodman')
            ->setRating(5)->setImage('assets/img/testimonials/testimonials-1.jpg');
        $manager->persist($review1);

        $review2 = new Review();
        $review2
            ->setApproved(false)
            ->setContent('
                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum
                suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.
                Maecen aliquam, risus at semper.'
            )->setEmail('s.w@example-mail.com')
            ->setName('Sarah Willson')
            ->setRating(5)->setImage('assets/img/testimonials/testimonials-2.jpg');
        $manager->persist($review2);

        $review3 = new Review();
        $review3
            ->setApproved(true)
            ->setContent('
                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum
                suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.
                Maecen aliquam, risus at semper.'
            )->setEmail('j.k@example-mail.com')
            ->setName('Jena Karlis')
            ->setRating(5)->setImage('assets/img/testimonials/testimonials-3.jpg');
        $manager->persist($review3);

        $review4 = new Review();
        $review4
            ->setApproved(true)
            ->setContent('
                Proin iaculis purus consequat sem cure digni ssim donec porttitora entum
                suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.
                Maecen aliquam, risus at semper.'
            )->setEmail('j.w@example-mail.com')
            ->setName('John Warson')
            ->setRating(4)->setImage('assets/img/testimonials/testimonials-4.jpg');
        $manager->persist($review4);
    }

    public function loadGalleryImages(ObjectManager $manager)
    {
        for ($i = 1; $i < 9; $i++) {
            $image = new Image();
            $image
                ->setAlt("gallery-$i")
                ->setFilename("gallery-$i.jpg");
            $manager->persist($image);
        }
    }

    public function loadBookings()
    {
        
    }

}
