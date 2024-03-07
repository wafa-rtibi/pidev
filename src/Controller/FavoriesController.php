<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Offre;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{
    //like => ajout lel favoris addFavoris blog lel user ,addFavori user

    #[Route('/like/{id_blog}', name: 'app_like')]
    public function like($id_blog,UtilisateurRepository $rep,ManagerRegistry $doctrine,Request $req): Response
    {
        $blog=$doctrine->getRepository(Offre::class)->find($id_blog);
    
       
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $blogur = $rep->findOneByEmail($email);
        $blog->addFavori($blogur);
        $blogur->addFavorisOffre($blog);
        $em = $doctrine->getManager();
        $em->persist($blog);
        $em->flush();
    
          // Récupérer l'URL referer pour rediriger l'utilisateur vers la même page
          $referer = $req->headers->get('referer');

          return $this->redirect($referer);

        
    }


    //deslike
    #[Route('/deslike/{id_blog}', name: 'app_deslike')]
    public function deslike($id_blog,UtilisateurRepository $rep,ManagerRegistry $doctrine,Request $req): Response
    {
        $blog=$doctrine->getRepository(Offre::class)->find($id_blog);
      
       
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $blogur = $rep->findOneByEmail($email);

        $blogur->removeFavorisOffre($blog);
        $blog->removeFavori($blogur);
        $em = $doctrine->getManager();
        $em->persist($blog);
        $em->flush();

      
            // Récupérer l'URL referer pour rediriger l'utilisateur vers la même page
            $referer = $req->headers->get('referer');

            return $this->redirect($referer);
       
    }


    //afficher les blogs favoris par user => getFavorisblog 
    #[Route('/favoris', name: 'app_favoris')]
    public function showFavirote(UtilisateurRepository $rep,Request $req): Response
    {
        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        $blogur = $rep->findOneByEmail($email);
        $favoris=$blogur->getFavorisOffres();
        // $favoris=$favoris->toArray();


        return $this->render('frontoffice/blog/favoris.html.twig',['favoris'=>$favoris]);
    }

}