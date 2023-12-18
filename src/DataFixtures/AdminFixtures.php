<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setEmail('admin@example.com')
            ->setUsername('Administrator')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('$2y$13$yfUpDE3isAV/gILH2PRpa.pqFagGzUQRtiwxnNfChBExfw84w5rcK');
        $manager->persist($admin);

        $manager->flush();
    }
}
