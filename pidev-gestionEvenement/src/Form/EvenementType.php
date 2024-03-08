<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['placeholder' => 'Enter le titre ..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),
                    new Regex([
                        'pattern' => '/\d/', // Disallow numbers
                        'match' => false,
                        'message' => 'Numbers are not allowed in this field.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => ['placeholder' => 'Enter la description..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),
                    new Regex([
                        'pattern' => '/\d/', // Disallow numbers
                        'match' => false,
                        'message' => 'Numbers are not allowed in this field.',
                    ]),
                ],
            ])
            ->add('lieu', TextType::class, [
                'attr' => ['placeholder' => 'Enter le lieu ..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),

                ],
            ])
            ->add('lien', TextType::class, [
                'attr' => [ 'id' => 'form_lieu','placeholder' => 'Enter le lien ..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),

                ],
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text', // Renders as a single input field
                'html5' => true, // Renders as an HTML5 input type (useful for modern browsers)
                'format' => 'yyyy-MM-dd', // Adjust the format according to your needs
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text', // Renders as a single input field
                'html5' => true, // Renders as an HTML5 input type (useful for modern browsers)
                'format' => 'yyyy-MM-dd', // Adjust the format according to your needs
            ])
            ->add('type');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
