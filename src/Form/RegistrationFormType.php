<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('nom', TextType::class, [
            'label' => 'Last Name',
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'First Name'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your full name',
                ]),
            ],
        ])
        ->add('prenom', TextType::class, [
            'label' => 'First Name',
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'First Name'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your full name',
                ]),
            ],
        ])
     
        // ->add('adresse', TextType::class, [
        //     'label' => 'Address',
        //     'required' => true,
        //     'constraints' => [
        //         new NotBlank([
        //             'message' => 'Please enter your address',
        //         ]),
        //     ],
        // ])
          
        ->add('email', EmailType::class, [
            'label' => 'Email Address',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your email address',
                ]),
                new Email([
                    'message' => 'Invalid email address format',
                ]),
            ],
        ])  
        ->add('tel', NumberType::class, [
            'label' => 'Phone Number',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Phone number'],
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your phone number',
                ]),
                new Regex([
                    'pattern' => '/^\d{8,}$/',
                    'message' => 'Invalid phone number format. Please enter an 8 digits number.',
                ]),
            ],
        ])

   
      
            // ->add('plainPassword', PasswordType::class, [
            //     'label' => 'Password',

                // instead of being set onto the object directly,
                // this is read and encoded in the controller
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password','class' => 'form-control', 'placeholder' => 'Password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
            
            // ->add('confirmPassword', PasswordType::class, [
            //     'label' => 'Confirm Password',
            //     'attr' => ['class' => 'form-control', 'placeholder' => 'Confirm your password here'],
            //     'mapped' => false, // Indicate field is not mapped to the database
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please confirm your password',
            //         ]),
            //         new EqualTo([
            //             'propertyPath' => 'plainPassword', // Compare with the password field
            //             'message' => 'Passwords must match',
            //         ]),
            //     ],
            // ])
            // ->add('plainPassword', RepeatedType::class, array(

            //     'type' => PasswordType::class,
            //     'attr' => ['autocomplete' => 'new-password','class' => 'form-control', 'placeholder' => 'Password'],
            //     'required' => true,
            //     'mapped' => false,
            //     'constraints' => array(
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ),
            //     'first_options'  => array('label' => 'Password'),
            //     'second_options' => array('label' => 'Confirm your password'),
            //     ))
                                
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        'placeholder' => 'Password',
                    ],
                ],
                'second_options' => [
                    'label' => 'Password Confirm',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        'placeholder' => 'Confirm Password',
                    ],
                ],
            ])
        ->add('agreeTerms', CheckboxType::class, [
            'label' => 'I agree with the terms of use',
            'label_attr' => [
                'class' => 'form-check-label',
            ],
            'mapped' => false,
            'constraints' => [
                new IsTrue([  
                    'message' => 'You should agree to our terms.',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
