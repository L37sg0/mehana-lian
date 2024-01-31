<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookingCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('email'),
            TextField::new('phone'),
            DateTimeField::new('reservation_date'),
            IntegerField::new('number_of_people'),
            TextareaField::new('message'),
        ];
    }
}