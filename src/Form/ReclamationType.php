<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Validator\Constraints\CustomNotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;






class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            //->add('description_reclamation')
            ->add('description_reclamation', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('type',ChoiceType::class,[
                  'choices'=>[
                    'conflit'=>'conflit',
                    'arnaque'=>'arnaque',
                    'autre'=>'autre',

                  ],
                  'placeholder' => 'Choose a type' ])
                  
             
                
            
            ->add('imageFile1', VichImageType::class, [
                    'required'=> $options['modifier']? false : true,
                    'label' => 'image 1'
                ]);
            
            $builder->add('imageFile2', VichImageType::class, [
                'required' => false,
                'label' => 'image 2'
            ])
            
            
            
            
                 
           
            ->add('save',SubmitType::class)


            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
   {
        $resolver->setDefaults([
           'data_class' => Reclamation::class,
           'modifier' => false, // Ajout d'une option 'modifier' par défaut à false
    
       ]);
    }
}
