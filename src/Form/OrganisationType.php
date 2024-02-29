<?php
/*
namespace App\Form;

use App\Entity\Organisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_organisation')
            ->add('description')
            ->add('rib')
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
*/
namespace App\Form;

use App\Entity\Organisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_organisation')
            ->add('description')
            ->add('rib', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le RIB ne peut pas être vide.',
                    ]),
                   /* new Length([
                        'min' => 12,
                        //'minMessage' => 'Le RIB doit faire au moins {{ limit }} caractères.',
                        'max' => 12,
                        //'maxMessage' => 'Le RIB ne peut pas dépasser {{ limit }} caractères.',
                    ]),*/
                    new Type([
                        'type' => 'alnum',
                        'message' => 'Le RIB doit être composé de chiffres et de lettres uniquement.',
                    ]),
                ],
            ])
            ->add('adresse');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
