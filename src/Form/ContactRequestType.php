<?php

namespace App\Form;

use App\Entity\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
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
            ->add('message', TextareaType::class)
            // On peut mettre le submit ici ou dans le twig
            // ->add('submit', submitType::class)
            //honey pot
            ->add('middle-name', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'hidden', 'tabindex' => '-1'], // Utiliser une classe pour le cacher avec CSS
                'constraints' => [
                    new Length([
                        'max' => 0,
                    ]),
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRequest::class,
        ]);
    }
}
