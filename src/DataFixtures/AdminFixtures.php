<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\ApiIntegration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

;

class AdminFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setEmail('admin@example.com')
            ->setUsername('Administrator')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('$2y$13$yfUpDE3isAV/gILH2PRpa.pqFagGzUQRtiwxnNfChBExfw84w5rcK');

        $apiIntegration = (new ApiIntegration())
            ->setClientId($_ENV['CLIENT_ID'])
            ->setClientSecret($this->passwordHasher->hashPassword($admin, $_ENV['CLIENT_SECRET']))
            ->setName('TEST Integration')
            ->setUser($admin);

        $manager->persist($admin);
        $manager->persist($apiIntegration);

        $manager->flush();
    }
}
