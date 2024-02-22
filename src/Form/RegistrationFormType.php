<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

       $builder ->add('nom', TextType::class, [
            'label' => 'First Name:',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your first name',
                ]),
            ],
            'attr' => ['class' => 'form-control', 'placeholder' => 'First Name'],
            'required' => true,
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Last Name:',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your last name',
                ]),
            ],
            'attr' => ['class' => 'form-control', 'placeholder' => 'Last Name'],
            'required' => true,
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email:',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your email address',
                ]),
                
            ],
            'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
            'required' => true,
        ])
        ->add('tel', TextType::class, [
            'label' => 'Phone Number:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Phone Number'],
            'required' => true,
            'invalid_message' => 'Please enter a valid phone number.',
        ])
            ->add('mdp', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'mapped' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                   
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
