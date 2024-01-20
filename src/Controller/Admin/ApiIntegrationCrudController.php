<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\ApiIntegration;
use App\Service\CredentialsGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApiIntegrationCrudController extends AbstractCrudController
{
    public function __construct(
        private CredentialsGenerator $credentialsGenerator
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
            TextField::new('clientId'),
            TextField::new('clientSecret')
        ];
    }

//    public function configureActions(Actions $actions): Actions
//    {
//        $actions =  parent::configureActions($actions);
////dd($actions);
//        $actions['new']['prePersist'] = function (AdminContext $context, ApiIntegration $apiIntegration) {
//            /** @var Admin $user */
//            $user = $this->getUser();
//            $apiIntegration->setUser($user);
//        };
//
//        $actions['edit']['preUpdate'] = function (AdminContext $context, ApiIntegration $apiIntegration) {
//            /** @var Admin $user */
//            $user = $this->getUser();
//            $apiIntegration->setUser($user);
//        };
//
//        return $actions;
//    }
    public function createEntity(string $entityFqcn)
    {
        /** @var ApiIntegration $apiIntegration */
        $apiIntegration = parent::createEntity($entityFqcn);
        extract($this->credentialsGenerator->generateCredentials());
        $apiIntegration
            ->setUser($this->getUser())
            ->setRoles(['API_USER'])->setClientId($clientId)->setClientSecret($clientSecret);

        return $apiIntegration;
    }

}
