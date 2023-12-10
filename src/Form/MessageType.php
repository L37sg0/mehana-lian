<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Symfony\Component\Translation\t;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please fill out this field.')),
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please fill out this field.')),
                    new Email(null, t('Please provide valid email address.'))
                ]
            ])
            ->add('subject', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(null, t('Please tell us the subject of your message.')),
                ]
            ])
            ->add('content', TextType::class, [
                'constraints' => [
                    new NotBlank(null, t('Don`t be lazy. Say something.')),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => t('Send Message'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
