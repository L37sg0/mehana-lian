<?php

namespace App\Controller\Admin;

use App\Entity\MenuItem;
use App\Service\CsvExporter;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;

class MenuItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MenuItem::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('slug'),
            TextField::new('title'),
            TextEditorField::new('ingredients'),
            NumberField::new('price'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $exportAction = Action::new('export')
            ->linkToCrudAction('export')
            ->addCssClass('btn btn-success')
            ->setIcon('fa fa-download')
            ->createAsGlobalAction();

        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $exportAction);
    }

    public function export(
        AdminContext $context,
        CsvExporter $csvExporter
    ): Response {

        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->container
            ->get(FilterFactory::class)
            ->create($context->getCrud()
                ->getFiltersConfig(),
                $fields,
                $context->getEntity()
            );
        $queryBuilder = $this->createIndexQueryBuilder($context->getSearch(),
            $context->getEntity(),
            $fields,
            $filters
        );
        $filename = 'MenuItemsExport-' . date('Y-m-d_H:i:s') . '.csv';

        $response = $csvExporter->createResponseFromQueryBuilder($queryBuilder, $fields, $filename);

        return $response;
    }

}
