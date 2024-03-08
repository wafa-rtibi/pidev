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
                    'Material goods' => 'Biens matériels',
                    'Food and agricultural products' => 'Aliments et produits agricoles',
                    'Services' => 'Services',
                    'Clothing and personal items' => 'Habillement et articles personnels',
                    'Household items' => 'Articles ménagers',
                    'Tools and equipment' => 'Outils et équipements',
                    'Works of art and handicrafts' => 'Œuvres d\'art et objets artisanaux',
                    'Digital Products' => 'Produits numériques ',
                    'Leisure items' => 'Articles de loisirs',
                    'Health and wellness products' => 'Produits de santé et de bien-être'
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
                'required'=> $options['modifier']? false : true,
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
