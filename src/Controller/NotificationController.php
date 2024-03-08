<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Repository\UtilisateurRepository;
use App\Service\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{

    //afficher notification
    #[Route( '/home',name: 'app_show_notif')]
    public function showNotification(NotificationRepository $repo, UtilisateurRepository $rep,Request $req): Response
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
}
