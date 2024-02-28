<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;  // Import the correct Request class;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserController extends AbstractController
{

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    // #[Route('/signin', name: 'app_sign_in')]
    // public function signIn(): Response
    // {
    //     return $this->render('backoffice/auth/signIn.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }

    // #[Route('/signup', name: 'app_sign_up')]
    // public function signup(Request $request, ManagerRegistry $doctrine,UserPasswordHasherInterface $userPasswordHasher ): Response
    // {
    //     $user = new Utilisateur();
    //     $registerform = $this->createForm(RegistrationFormType::class, $user);
    //     $registerform->add('Signup',SubmitType::class); 
    //     $registerform->handleRequest($request);


     
    //     if ($registerform->isSubmitted() && $registerform->isValid()) {
    //         // encode the plain password
    //         // $user->setMdp(
    //         //     $userPasswordHasher->hashPassword(
    //         //         $user,
    //         //         $form->get('mdp')->getData()
    //         //     )
    //         // );

    //         $user->setdateinscription(new \DateTime('now'));

    //         $entityManager =$doctrine->getManager();
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return   $this->json($user) ;

    //     }
    //     return $this->render('backoffice/auth/signup.html.twig', [
    //         'registerform' => $registerform->createView(),
    //     ]);
           
    // }
    #[Route('/adduser', name: 'app_add_user')] 
    public function adduser(Request $request, ManagerRegistry $doctrine , EntityManagerInterface $entityManager ): Response
     {
       //    $user = new Utilisateur();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     $user->setDateInscription(new \DateTime('now'));

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Handle file uploads (if applicable)
    //         $photo = $form->get('photo_profil')->getData();
    //         if ($photo) {
    //             // Move the file to the desired location and set its path on the entity
    //             $photoFileName = uniqid().'.'.$photo->guessExtension();
    //             $photo->move(
    //                 $this->getParameter('upload_directory'), // Replace with the actual upload directory parameter
    //                 $photoFileName
    //             );
    //             $user->setPhotoProfil($photoFileName);
    //         }

    //         // Persist user
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         // Redirect to success page or display a success message
    //         return $this->redirectToRoute('backoffice/user/user-list.html.twig'); 
    //     }

    //     return $this->render('backoffice/user/user-add.html.twig', [
    //         'form' => $form->createView(),
    //         'controller_name' => 'UserController',
    //     ]);
    $user = new Utilisateur();
    $form = $this->createForm(UserType::class, $user);
    $form->add('ADD',SubmitType::class);   
    $form->handleRequest($request);  
 
    if ($form->isSubmitted() && $form->isValid()) {
        // Get validated data from the form
       // dump($form->getData());
       $user->setdateinscription(new \DateTime('now'));

        // Persist the user entity
        $entityManager =$doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User created successfully!');
        return $this->redirectToRoute('app_list_user');

        // Redirect to user list page
    }

    return $this->render('backoffice/user/user-add.html.twig', [
        'form' => $form->createView()
    //    , 'controller_name' => 'UserController',

    ]);
    }
        
    

    
    #[Route('/listuser', name: 'app_list_user')]
    public function listuser(ManagerRegistry $doctrine): Response
    {

        $user= $doctrine->getRepository(Utilisateur::class);
        $users=$user->findAll();

        return $this->render('backoffice/user/user-list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,

        ]);    
    }
 



    #[Route('/edituser/{id}', name: 'app_edit_user')]         
public function edituser(Request $request, int $id, EntityManagerInterface $entityManager): Response
{// Fetch the user to be edited
    $userRepository = $entityManager->getRepository(Utilisateur::class);
    $user = $userRepository->findOneById($id);
    
    // Create the form with Edit button
    $form = $this->createForm(UserType::class, $user);
    $form->add('Edit', SubmitType::class);

    // Handle form submission
    if ($request->isMethod('POST')) {
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('message','User information updated successfully!');
                return $this->redirectToRoute('app_list_user');
      
        }
    }

    // Render the edit form
    return $this->render('backoffice/user/user-edit.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
}

    #[Route('/deleteuser/{id}', name: 'app_delete_user')]
    public function delete(int $id, EntityManagerInterface $entityManager,ManagerRegistry $doctrine ): Response
    {
        $userRepository = $entityManager->getRepository(Utilisateur::class);
        $user = $userRepository->find($id);
      
        
        $entityManager =$doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();  
        return $this->redirectToRoute('app_list_user');
    
}


#[Route('/profile', name: 'app_user_profile')]
public function profile(): Response
{
    // Get the current user (assuming you are using Symfony's security)
    $user = $this->getUser();

    return $this->render('user/profile.html.twig', [
        'user' => $user,
    ]);
}


}