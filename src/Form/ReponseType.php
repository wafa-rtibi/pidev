<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Validator\Constraints\CustomNotBlank;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('description')
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'rows' => 8, // Nombre de lignes
                    'cols' => 80, // Nombre de colonnes
                ],
                ])
                // Ajout du champ de type checkbox
                ->add('compensation', CheckboxType::class, [
                    'label' => 'compensation', // Libellé du champ
                    'required' => false, // Le champ n'est pas obligatoire
                ])
                ->add('save', SubmitType::class)
            ;
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
