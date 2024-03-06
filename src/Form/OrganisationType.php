<?php

namespace App\Form;

use App\Entity\Organisation;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_organisation', TextType::class, [
                'label' => 'Nom de l\'organisation',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('image', FileType::class, [
                'label' => 'Logo Organisation',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('rib', TextType::class, [
                'label' => 'RIB',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le RIB ne peut pas être vide.',
                    ]),
                    /* new Length([
                        'min' => 12,
                        //'minMessage' => 'Le RIB doit faire au moins {{ limit }} caractères.',
                        'max' => 12,
                        //'maxMessage' => 'Le RIB ne peut pas dépasser {{ limit }} caractères.',
                    ]), */
                    new Type([
                        'type' => 'alnum',
                        'message' => 'Le RIB doit être composé de chiffres et de lettres uniquement.',
                    ]),
                ],
                'required' => true,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
