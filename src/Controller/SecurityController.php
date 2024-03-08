<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException; // For enhanced security



class SecurityController extends AbstractController
{

   
    private $session; // Ajout de la propriété $session

    public const LOGIN_ROUTE = 'app_login'; //nom de route de login

    public function __construct(SessionInterface $session) //genere url
    {
    
    $this->session = $session;
 // Assurez-vous que la propriété $session est déclarée dans la classe

    }



    

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        if ($user instanceof Utilisateur && $user->getIsActive()) {
            return $this->redirectToRoute('app_home');
        } elseif ($user instanceof Utilisateur && !$user->getIsActive()) {
            // Blocked user, add flash message and redirect to login page
            $this->addFlash('danger', 'Your account is blocked. Please contact the administrator.');

            return $this->redirectToRoute('app_login');
        }
        // if (!$user->isActive()) {
        //     // User is blocked, display error message
        //     $this->addFlash('error', 'Your account is currently blocked. Please contact support.');
        //     return $this->redirectToRoute('app_login');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,      

        ]);
    }
  
    //  
    #[Route(path: '/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}