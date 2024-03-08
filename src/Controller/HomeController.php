<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(NotificationRepository $repo, UtilisateurRepository $rep,HttpFoundationRequest $req): Response
    {
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $user = $rep->findOneByEmail($email);
        $notifications = $repo->findByUser($user->getId()); 
        // Récupérer l'URL referer pour rediriger l'utilisateur vers la même page
        // $referer = $req->headers->get('referer');

        // return $this->render($referer, ['notifications'=>$notifications]);

                 return $this->render('frontoffice/home/home.html.twig', ['notifications'=>$notifications]);
    }
    
  
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('frontoffice/home/aboutUs.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
   
}
