<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class OffreType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Biens matériels' => 'Biens matériels',
                    'Aliments et produits agricoles' => 'Aliments et produits agricoles',
                    'Services' => 'Services',
                    'Habillement et articles personnels' => 'Habillement et articles personnels',
                    'Articles ménagers' => 'Articles ménagers',
                    'Outils et équipements' => 'Outils et équipements',
                    'Œuvres d\'art et objets artisanaux' => 'Œuvres d\'art et objets artisanaux',
                    'Produits numériques' => 'Produits numériques ',
                    'Articles de loisirs' => 'Articles de loisirs',
                    'Produits de santé et de bien-être' => 'Produits de santé et de bien-être'
                ]
            ])
            ->add('description')


        // ->add('date_publication')
        // ->add('photos',FileType::class,[
        //     'label'=>false,
        //     'multiple'=>true,
        //     'required'=>true,
        // ])
        // ->add('offreur')
       

          ->add('imageFile1', VichImageType::class, [
                'required' => true,
                'label' => 'image 1'
            ]);
        
        $builder->add('imageFile2', VichImageType::class, [
            'required' => false,
            'label' => 'image 2'
        ])
            ->add('imageFile3', VichImageType::class, [
                'required' => false,
                'label' => 'image 3'
            ]);
        if ($options['modifier']) {
            $builder->add('etat', ChoiceType::class, [

                'choices' => [
                    'publié' => 'publié',
                    'reservé' => 'reservé'
                ]
            ]);
        }
        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
            'modifier' => false,
            // ma3neha false par defaut tetbadel fel create form
        ]);
    }
}
