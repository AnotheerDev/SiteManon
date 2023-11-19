<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez une adresse email.',
                        ]),
                    ],
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un pseudo.',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre pseudo doit contenir {{ limit }} caractères au minimum.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les termes et conditions.",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Repetez le mot de passe'],
                'error_bubbling' => true, // Move the error message to the end of the form
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caractères au minimum.',
                        // You can set a max length as well if needed
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                        'message' => "Votre mot de passe doit contenir au moins une majuscule et un chiffre ainsi qu'un caractère spécial.",
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
