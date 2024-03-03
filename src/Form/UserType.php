<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;/// hedha w li lfouq lel validation mtaa lmot de passe 
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\DataTransformer\PhotoProfileTransformer;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
         
       
        $builder
        // ->add('photoprofil_file', VichImageType::class, [
        //     'attr' => ['class' => 'form-control'],
        //     'label' => 'Profile Photo:',
        //     'required' => true,
        // ])
        ->add('nom', TextType::class, [
            'label' => 'First Name:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'First Name'],
            'required' => true,
        ])

        ->add('prenom', TextType::class, [
            'label' => 'Last Name:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Last Name'],
            'required' => true,
        ])
      
        ->add('email', EmailType::class, [
            'label' => 'Email:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
            'required' => true,
            'invalid_message' => 'Please enter a valid email address.',
        ])
       
        ->add('adresse', TextType::class, [
            'label' => 'Address:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Address'],
            'required' => true,
        ])
        ->add('tel', TextType::class, [
            'label' => 'Phone Number:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Phone Number'],
            'required' => true,
            'invalid_message' => 'Please enter a valid phone number.',
        ])

        // ->add('mdp', PasswordType::class, [
        //     'label' => 'Password:',
        //     'attr' => ['class' => 'form-control', 'placeholder' => 'Password'],
        //     'required' => true,
        // ])
      
      
        ->add('Username', TextType::class, [
            'label' => 'Username:',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Username'],
            'required' => true,
        ])
        ->add('mdp', PasswordType::class, [
            'attr' => ['class' => 'form-control', 'placeholder' => 'Password'],
            'label' => 'Password :',
            'required' => true,
        ]);
        // $builder->get('photoprofil')
        // ->addModelTransformer(new PhotoProfileTransformer())
        // ->add('photoprofil', FileType::class, [
        //     'attr' => ['class' => 'form-control'],
        //     'label' => 'Profile Photo:',
        //     'required' => false,
        //     // Consider adding validation constraints for file types, size, etc.
        // ]);
        ;
        
        // $builder->get('photoprofil') // Now you can access the field
        //     ->addModelTransformer(new PhotoProfileTransformer());
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
