<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Symfony\Component\Translation\t;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please fill out this field.'))
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please fill out this field.')),
                    new Email(null, t('Please provide valid email address.'))
                ],
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please fill out this field.')),
                ]
            ])
            ->add('date', DateType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please pick a date for your reservation.')),
                ],
            ])
            ->add('seats', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please select number of seats.'))
                ],
            ])
            ->add('message', TextareaType::class)

            ->add('submit', SubmitType::class, [
                'label' => t('Book a Table'),
            ])
            ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Booking::class]);
    }
}