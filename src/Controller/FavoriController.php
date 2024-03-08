<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Offre;
use App\Repository\UtilisateurRepository;
use App\Service\NotificationManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{
    //like => ajout lel favoris addFavoris offre lel user ,addFavori user

    #[Route('/like/{id_offre}', name: 'app_like')]
    public function like($id_offre,UtilisateurRepository $rep,ManagerRegistry $doctrine,Request $req,NotificationManager $notificationManager): Response
    {
        $offre=$doctrine->getRepository(Offre::class)->find($id_offre);
    
       
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $offreur = $rep->findOneByEmail($email);
        $offre->addFavori($offreur);
        $offreur->addFavorisOffre($offre);
        $em = $doctrine->getManager();
        $em->persist($offre);
        $em->flush();

        $message =  $offreur->getNom()."  like your offer ".$offre->getNom();

        // Appel du service de notification pour envoyer la notification
        $notificationManager->sendNotification($offre->getOffreur(), $message);
    
          // Récupérer l'URL referer pour rediriger l'utilisateur vers la même page
          $referer = $req->headers->get('referer');

          return $this->redirect($referer);

        
    }


    //deslike
    #[Route('/deslike/{id_offre}', name: 'app_deslike')]
    public function deslike($id_offre,UtilisateurRepository $rep,ManagerRegistry $doctrine,Request $req): Response
    {
        $offre=$doctrine->getRepository(Offre::class)->find($id_offre);
      
       
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $offreur = $rep->findOneByEmail($email);

        $offreur->removeFavorisOffre($offre);
        $offre->removeFavori($offreur);
        $em = $doctrine->getManager();
        $em->persist($offre);
        $em->flush();

      
            // Récupérer l'URL referer pour rediriger l'utilisateur vers la même page
            $referer = $req->headers->get('referer');

            return $this->redirect($referer);
       
    }


    //afficher les offres favoris par user => getFavorisoffre 
    #[Route('/favoris', name: 'app_favoris')]
    public function showFavirote(UtilisateurRepository $rep,PaginatorInterface $paginator,Request $req): Response
    {
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $offreur = $rep->findOneByEmail($email);
        $favoris=$offreur->getFavorisOffres();
        $favoris=$favoris->toArray();

        $pagination= $paginator->paginate(
            $favoris,
            $req->query->get('page', 1),
            2
        );
        return $this->render('frontoffice/offre/favoris.html.twig',['favoris'=>$pagination]);
    }

}
