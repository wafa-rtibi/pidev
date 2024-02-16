<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           # ->add('nom')
           # ->add('Last_name')
           # ->add('Email_address')
            ->add('objet_reclamation')
            ->add('description_reclamation')
            ->add('date_reclamation')
            ->add('save',SubmitType::class)

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
   {
        $resolver->setDefaults([
           'data_class' => Reclamation::class,
    
       ]);
    }
}
