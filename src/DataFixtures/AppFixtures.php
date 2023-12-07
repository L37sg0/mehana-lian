<?php

namespace App\DataFixtures;

use App\Entity\Block;
use App\Entity\Image;
use App\Entity\Page;
use App\Entity\Post;
use App\Entity\Profile;
use App\Entity\Review;
use App\Entity\Text;
use App\Repository\ProfileRepository;
use App\Repository\ReviewRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private ReviewRepository $reviewRepository,
        private ProfileRepository $profileRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadHomePage($manager);
        

        $manager->flush();
    }
    
    public function loadHomePage(ObjectManager $manager): void
    {
        
        // Home Block1 Post
        $content1 = new Text();
        $content1
            ->setSlug('post-hero')
            ->setValue(
                'Sed autem laudantium dolores.
                 Voluptatem itaque ea consequatur eveniet.
                  Eum quas beatae cumque eum quaerat.'
            );
        $manager->persist($content1);

        $image1 = new Image();
        $image1
            ->setAlt('hero dish')
            ->setFilename('hero-img.png')
            ->setPath('assets/img/hero-img.png');
        $manager->persist($image1);

        $post1 = new Post();
        $post1
            ->setTitle('Enjoy Your Healthy Delicious Food')
            ->setSlug('enjoy-your-healthy-delicious-food')
            ->setContent($content1)
            ->addImage($image1);
        $manager->persist($post1);
        
        // Home Page Blocks
        $block1 = new Block();
        $block1->setTitle('Home Hero')
            ->setSlug('home-hero')
            ->addPost($post1);
        $manager->persist($block1);
        
        $this->loadReviews($manager);
        $block2 = new Block();
        $block2->setTitle('What Are They Saying About Us')->setSlug('home-testimonials');
        foreach ($this->reviewRepository->findAll() as $review) {
            $block2->addReview($review);
        }
        $manager->persist($block2);

        $this->loadProfiles($manager);
        $block3 = new Block();
        $block3->setTitle('Our Professional Chefs')->setSlug('home-chefs');
        foreach ($this->profileRepository->findAll() as $profile) {
            $block3->addProfile($profile);
        }
        $manager->persist($block3);
        
        // Create Home Page
        $page1 = new Page();
        $page1
            ->setTitle('Home')
            ->setSlug('home')
            ->addBlock($block1)
            ->addBlock($block2)
            ->addBlock($block3);
        $manager->persist($page1);

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
            ->setFirstname('Saul')
            ->setLastname('Goodman')
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
            ->setFirstname('Sarah')
            ->setLastname('Willson')
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
            ->setFirstname('Jena')
            ->setLastname('Karlis')
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
            ->setFirstname('John')
            ->setLastname('Warson')
            ->setRating(4)->setImage('assets/img/testimonials/testimonials-4.jpg');
        $manager->persist($review4);
        
        $manager->flush();
    }
    
    public function loadProfiles(ObjectManager $manager): void
    {
        $profile1 = new Profile();
        $profile1
            ->setFirstname('Walter')
            ->setLastname('White')
            ->setPosition('Master Chef')
            ->setImage('assets/img/chefs/chefs-1.jpg')
            ->setDescription('Velit aut quia fugit et et.
                 Dolorum ea voluptate vel tempore tenetur ipsa quae aut.
                  Ipsum exercitationem iure minima enim corporis et voluptate.'
            );
        $manager->persist($profile1);

        $profile2 = new Profile();
        $profile2
            ->setFirstname('Sarah')
            ->setLastname('Johnson')
            ->setPosition('Patissier')
            ->setImage('assets/img/chefs/chefs-2.jpg')
            ->setDescription('Velit aut quia fugit et et.
                 Dolorum ea voluptate vel tempore tenetur ipsa quae aut.
                  Ipsum exercitationem iure minima enim corporis et voluptate.'
            );
        $manager->persist($profile2);

        $profile3 = new Profile();
        $profile3
            ->setFirstname('William')
            ->setLastname('Andresson')
            ->setPosition('Cook')
            ->setImage('assets/img/chefs/chefs-3.jpg')
            ->setDescription('Velit aut quia fugit et et.
                 Dolorum ea voluptate vel tempore tenetur ipsa quae aut.
                  Ipsum exercitationem iure minima enim corporis et voluptate.'
            );
        $manager->persist($profile3);

        $manager->flush();
    }

    public function loadPages(ObjectManager $manager): void
    {
        $page2 = new Page();
        $page2
            ->setTitle('About')
            ->setSlug('about');
        $manager->persist($page2);

        $page3 = new Page();
        $page3
            ->setTitle('Menu')
            ->setSlug('menu');
        $manager->persist($page3);

        $page4 = new Page();
        $page4
            ->setTitle('Events')
            ->setSlug('events');
        $manager->persist($page4);

        $page5 = new Page();
        $page5
            ->setTitle('Gallery')
            ->setSlug('gallery');
        $manager->persist($page5);

        $page6 = new Page();
        $page6
            ->setTitle('Contact')
            ->setSlug('contact');
        $manager->persist($page6);

        $page7 = new Page();
        $page7
            ->setTitle('Book a table')
            ->setSlug('book-a-table');
        $manager->persist($page7);
    }

    public function loadBlocks(ObjectManager $manager): void
    {

        // Header Blocks
        $block0 = new Block();
        $block0->setTitle('Header Content')->setSlug('header');
        $manager->persist($block0);



        // About Page Blocks
        $block4 = new Block();
        $block4->setTitle('Learn More About Us')->setSlug('about-us');
        $manager->persist($block4);

        $block5 = new Block();
        $block5->setTitle('Why Choose Us')->setSlug('about-why-us');
        $manager->persist($block5);

        $block6 = new Block();
        $block6->setTitle('About Stats')->setSlug('about-stats');
        $manager->persist($block6);

        // Menu Page Blocks
        $block7 = new Block();
        $block7->setTitle('Check Our Menu')->setSlug('menu');
        $manager->persist($block7);

        // Events Page Blocks
        $block8 = new Block();
        $block8->setTitle('Share Your Moments In Our Restaurant')->setSlug('events');
        $manager->persist($block8);

        // Events Page Blocks
        $block8 = new Block();
        $block8->setTitle('Share Your Moments In Our Restaurant')->setSlug('events');
        $manager->persist($block8);

        // Gallery Page Blocks
        $block9 = new Block();
        $block9->setTitle('Check Our Gallery')->setSlug('gallery');
        $manager->persist($block9);

        // Contact Page Blocks
        $block10 = new Block();
        $block10->setTitle('Need Help? Contact Us')->setSlug('contact');
        $manager->persist($block10);

        // Book a Table Page Blocks
        $block11 = new Block();
        $block11->setTitle('Book Your Stay With Us')->setSlug('book-a-table');
        $manager->persist($block11);

        // Footer Blocks
        $block12 = new Block();
        $block12->setTitle('Footer Content')->setSlug('footer');
        $manager->persist($block12);
    }


}
