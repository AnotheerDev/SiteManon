<?php

namespace App\Form;

use App\Entity\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName',TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstName',TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('email',EmailType::class)
            ->add('subject',TextType::class, [
                'label' => 'Sujet'
            ])
            ->add('message', TextareaType::class, [
                'placeholder' => 'Votre message',
            ])
            // On peut mettre le submit ici ou dans le twig
            // ->add('submit', submitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRequest::class,
        ]);
    }
}
