<?php

namespace App\Controller\Admin;

use App\Entity\ApiIntegration;
use App\Service\CredentialsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiIntegrationCrudController extends AbstractCrudController
{

    public function __construct(
        private CredentialsGenerator $credentialsGenerator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return ApiIntegration::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('clientId')
                ->setFormTypeOption('attr', ['readonly' => true]),
            TextField::new('clientSecret')
                ->setFormTypeOption('attr', ['readonly' => true])
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        /** @var ApiIntegration $apiIntegration */
        $apiIntegration = new $entityFqcn();
            $credentials = $this->credentialsGenerator->generateCredentials();
            $clientId = $credentials['clientId'];
            $clientSecret = $credentials['clientSecret'];

            $apiIntegration
                ->setClientId($clientId)
                ->setClientSecret($clientSecret);

        return $apiIntegration;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var ApiIntegration $entityInstance */
        $clientSecret = $entityInstance->getClientSecret();
        $clientSecretHash = $this->passwordHasher->hashPassword($entityInstance, $clientSecret);

        $entityInstance->setClientSecret($clientSecretHash)
            ->setUser($this->getUser());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
