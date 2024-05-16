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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UtilisateurRepository;  
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; // Import the class
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Throwable;
use Symfony\Component\HttpFoundation\RedirectResponse;


class UserController extends AbstractController
{

    private Security $security ;
    // public function __construct(Security $security)
    // {
    //     $this->security = $security;
    // }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
   
    #[Route('/adduser', name: 'app_add_user')] 
    public function adduser(Request $request, ManagerRegistry $doctrine , EntityManagerInterface $entityManager ): Response
     {
    $user = new Utilisateur();
    $form = $this->createForm(UserType::class, $user);
    $form->add('ADD',SubmitType::class);   
    $form->handleRequest($request);  
 
    if ($form->isSubmitted() && $form->isValid()) {
        // Get validated data from the form
       // dump($form->getData());
       $user->setdateinscription(new \DateTime('now'));
       $user->setRoles(['ROLE_USER']);

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
public function listuser(Request $request, UtilisateurRepository $utilisateurRepository,Security $security): Response
{
    // Check if the user has the required role to access the /listuser route
    // if (!$security->isGranted('ROLE_ADMIN')) {
    //     // Redirect the user to the login page
    //     return $this->redirectToRoute('app_login');
    // }
    $user = $security->getUser();


    // if (!$user || !in_array('ROLE_ADMIN', $user->getRoles(), true)) {
    //     return new RedirectResponse($this->generateUrl('app_home'));
    // }

    $search = $request->query->get('search');
    $status = $request->query->get('status');

    // Use the injected 'UtilisateurRepository'
    $users = $utilisateurRepository->filterBySearchOrStatus($search, $status);

    return $this->render('backoffice/user/user-list.html.twig', [
        'controller_name' => 'UserController',
        'users' => $users,
        'search' => $search,
        'status' => $status,
    ]);


}


// #[Route('/showprofile', name: 'app_show_user_profile')]
// public function userProfile(UtilisateurRepository $utilisateurRepository): Response
// {
//     // Get the connected user
//     $user = $this->getUser();

//     if (!$user instanceof Utilisateur) {
//         return $this->redirectToRoute('app_login'); // Redirect to login if not logged in
//     }

//     // Fetch the connected user's data using the ID
//     $utilisateur = $utilisateurRepository->find($user->getId());

//     // Render the profile template with the user data
//     return $this->render('security/profile.html.twig', [
//         'utilisateur' => $utilisateur,
//     ]);
// }



#[Route('/showprofile', name: 'app_show_user_profile')]
public function userProfile(UtilisateurRepository $utilisateurRepository): Response
{
    try {
        // Get the connected user
        $user = $this->getUser();

        // // Redirect to login if not logged in (check for null and type)
        // if (!$user || !$user instanceof Utilisateur) {
        //     return $this->redirectToRoute('app_login');
        // }

        // Optional: Fetch additional user data from repository if needed
        // $utilisateur = $utilisateurRepository->find($user->getId());

        return $this->render('frontoffice/user/profile.html.twig', [
            'user' => $user,
        ]);
    } catch (Throwable $e) {
        $this->addFlash('error', $e->getMessage());
        return $this->redirectToRoute('app_home'); // Redirect to a safe location on error
    }
}



  

  // // Get the connected user
    // $user = $this->getUser();

    // if (!$user instanceof Utilisateur) {
    //     return $this->redirectToRoute('app_login'); // Redirect to login if not logged in
    // }

    // // Fetch the connected user's data using the ID
    // $utilisateur = $utilisateurRepository->find($user->getId());

    // // Check if user was found (optional)
    // if (!$utilisateur) {
    //     throw new NotFoundHttpException("User not found."); // Handle user not found case
    // }

    // // Render the profile template with the user data
    // return $this->render('security/profile.html.twig', [
    //     'utilisateur' => $utilisateur,
    // ]);
   



    #[Route('/edituser/{id}', name: 'app_edit_user')]         
    public function edituser(Request $request, int $id, EntityManagerInterface $entityManager, ManagerRegistry $doctrine ): Response
    {// Fetch the user to be edited
    $userRepository = $entityManager->getRepository(Utilisateur::class);
    $user = $userRepository->findOneById($id);
    
    // Create the form with Edit button
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);  
    $form->add('Edit', SubmitType::class);

    // Handle form submission
 
    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User updated successfully!');
        return $this->redirectToRoute('app_list_user');
        }
     
    // Render the edit form
    return $this->render('backoffice/user/user-edit.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
    }



    
   


    #[Route('/block-user/{id}', name: 'block_user')]
    public function blockUser(int $id,Utilisateur $user,EntityManagerInterface $entityManager,ManagerRegistry $doctrine , MailerInterface $Mailer): Response
    {   
        $userRepository = $entityManager->getRepository(Utilisateur::class);
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
      
        // Vérifier si l'utilisateur actuel est administrateur
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Mettre à jour l'attribut isActive de l'utilisateur
        $user->setIsActive(false);
        $entityManager =$doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $email = (new Email())
            ->from('amaraoumayma0@gmail.com')
            ->to('raedmaaloul3@gmail.com')
            ->subject('You Are blocked :)!')
            ->text(' Admin has blocked you  ');

        $Mailer->send($email);
        $this->addFlash('success', 'User has been blocked successfully.');
        
        // Rediriger vers la liste des utilisateurs ou une autre page
        return $this->redirectToRoute('app_list_user');
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


#[Route('/profile/{id}', name: 'app_user_profile')] 
public function profile(int $id,EntityManagerInterface $entityManager,Request $request,Security $security, EntityManagerInterface $em): Response
{
    // Get the current user (assuming you are using Symfony's security)
    $userRepository = $entityManager->getRepository(Utilisateur::class);
    $user = $userRepository->findOneById($id);
    
    // Create the form with Edit button
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);  
    $form->add('Edit', SubmitType::class);

    // Handle form submission
 
    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'User updated successfully!');
        return $this->redirectToRoute('app_list_user');
        }
     
    // Render the edit form
    return $this->render('backoffice/user/user-edit.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
    }


public function search(Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');

        $qb = $em->createQueryBuilder('u')
            ->from('App\Entity\User', 'u')
            ->where('u.name LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->orWhere('u.address LIKE :search')
            ->orWhere('u.role LIKE :search')
            ->setParameter('search', "%$search%");

        $users = $qb->getQuery()->getResult();

        return $this->render('user/search_results.html.twig', [
            'users' => $users,
        ]);
    }
    
}