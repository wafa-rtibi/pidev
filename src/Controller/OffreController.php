<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OffreType;
use App\Entity\Offre;
use App\Repository\DemandeOffreRepository;
use App\Repository\OffreRepository;
use App\Repository\UtilisateurRepository;
use App\Service\WordFilterService;
use DateTime;

class OffreController extends AbstractController
{
    // afficher tous les offres ou les offres cherchées

    #[Route('/offres', name: 'app_all_offre')]
    public function afficherAll(Request $req, OffreRepository $rep, PaginatorInterface $paginator): Response
    {
        $offres = $rep->findAllNotReserved();
        $searchTerm = $req->query->get('searchTerm');



        if ($searchTerm) {
            $offres = $rep->search($searchTerm);
        }

        $pagination = $paginator->paginate(
            $offres,
            $req->query->get('page', 1), //page 1 par defaut
            2 // deux offres par page
        );


        return $this->render('frontoffice/offre/listOffre.html.twig', [

            'pagination' => $pagination,

        ]);
    }





    // ajout d'une offre 
    #[Route('/offre/add', name: 'app_offre_add')]
    public function  form(ManagerRegistry $doctrine, Request  $req, WordFilterService $wordFilterService): Response
    {
        $offreur = $this->getUser();

        $offre = new Offre();
        // $offreur = $doctrine->getRepository(Utilisateur::class)->find($id);
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            // houni filtrage des mauvaises mots b service eli sna3tou
            $description_entree = $offre->getDescription();
            $titre_entree = $offre->getNom();

            $offre->setDescription($wordFilterService->filterWords($description_entree));
            $offre->setNom($wordFilterService->filterWords($titre_entree));

            // hounii detecte date d'ajout 
            $dateActuelle = new DateTime();
            $offre->setDatePublication($dateActuelle);

            $offre->setOffreur($offreur);


            //  l'ajout lel DB
            $em = $doctrine->getManager();
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('app_Offre_added');
        }

        return $this->renderForm('frontoffice/offre/addOffre.html.twig', [
            'form' => $form,
        ]);
    }


    // afficher mes offres (user connecté)

    #[Route('/offre/my', name: 'app_my_offre')]
    public function afficherMyOffer(OffreRepository $repo, UtilisateurRepository $rep, PaginatorInterface $paginator, Request $req): Response
    {
        $user = $this->getUser();
        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $offreur = $rep->findOneByEmail($email);


        $offres = $offreur ? $repo->findByUser($offreur->getId()) : [];


        //$offres = $offreur->getOffres(); autre methode
        $pagination = $paginator->paginate(
            $offres,
            $req->query->get('page', 1),
            2


        );

        return $this->render('frontoffice/offre/myOffres.html.twig', [
            'offres' => $pagination,

        ]);
    }


    // modifier offre
    #[Route('/offre/update/{id_offre}', name: 'app_update_offre')]

    public function modifierOffre(Request $req, $id_offre, ManagerRegistry $doctrine, OffreRepository $repo, WordFilterService $wordFilterService, UtilisateurRepository $rep): Response
    {
        $user = $this->getUser();
        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $offreur = $rep->findOneByEmail($email);


        $offre = $repo->findByUserAndOffre($id_offre, $offreur->getId());
        $offre = $offre[0];


        // houni ki maydakhalech des images jdod yqodou el qdom mawwjoudin
        $image1 = $offre->getImageFile1();
        $image2 = $offre->getImageFile2();
        $image3 = $offre->getImageFile3();

        $form = $this->createForm(OffreType::class, $offre, ['modifier' => true]);

        $form->handleRequest($req);


        if ($form->isSubmitted() && $form->isValid()) {

            //naraja3 les images el qdom fi 7alet matbadlouch les images 

            if ($offre->getImageFile1() == null) {
                $offre->setImageFile1($image1);
            }


            if ($offre->getImageFile2() == null) {
                $offre->setImageFile2($image2);
            }


            if ($offre->getImageFile3() == null) {
                $offre->setImageFile3($image3);
            }

            $description_entree = $offre->getDescription();
            $titre_entree = $offre->getNom();

            $offre->setDescription($wordFilterService->filterWords($description_entree));
            $offre->setNom($wordFilterService->filterWords($titre_entree));

            //  l'ajout lel DB
            $em = $doctrine->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute('app_my_offre');
        }

        return $this->renderForm('frontoffice/offre/addOffre.html.twig', [
            'form' => $form,
            'update' => true,
        ]);
    }

    //supression
    #[Route('/offre/delete/{id_offre}', name: 'app_delete_offre')]
    public function supprimerOffre($id_offre, ManagerRegistry $doctrine, UtilisateurRepository $rep): Response
    {
        $offre = $doctrine->getRepository(Offre::class)->find($id_offre);
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $offreur = $rep->findOneByEmail($email);


        if ($offre->getDemandes() != null) {
            $offre->removeDemande($offre->getDemandes());
        }

        $offreur->removeOffre($offre); // zeyda 

        $em = $doctrine->getManager();
        $em->remove($offre);
        $em->flush();

        return $this->redirectToRoute('app_my_offre');
    }

    // page de message d'ajout d'une offre
    #[Route('/offre/sucess', name: 'app_Offre_added')]
    public function afficherMessage(ManagerRegistry $doctrine, UtilisateurRepository $rep): Response
    {
        // $user = $doctrine->getRepository(Utilisateur::class)->find($id);*
        $user = $this->getUser();
        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $offreur = $rep->findOneByEmail($email);

        return $this->render('frontoffice/offre/offerAdded.html.twig', [
            'user_name' => $offreur->getPrenom(),
            'user_id' => $offreur->getId(),
        ]);
    }

    //afficher un seule offre
    #[Route('/offre/single/{id_offre}', name: 'app_offre_details')]
    public function afficherDetailsOffre($id_offre, OffreRepository $rep, UtilisateurRepository $repo, DemandeOffreRepository $repi): Response
    {
        $mine = false;
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $user = $repo->findOneByEmail($email);

        $offre = $rep->find($id_offre);

        $offreur = $offre->getOffreur();


        if ($offreur->getId() == $user->getId()) $mine = true;
        //tester si la demande deja envoye
        $existe = $repi->findIfSended($offre->getId(), $user->getId());

        if ($existe) {
            $message = true;
        } else {
            $message = false;
        }

        return $this->render('frontoffice/offre/single_offre.html.twig', [
            'offre' => $offre,
            'mine' => $mine,
            'offreur' => $offreur,
            'msg' => $message,
        ]);
    }

    // show Categories
    #[Route('/offre/categories', name: 'app_show_categories')]
    public function showCategories(): Response
    {

        return $this->render('frontoffice/offre/categories.html.twig', []);
    }

    //show offer by categories
    #[Route('/offre/categories/{category}', name: 'app_offer_categories')]
    public function showOfferByCategories($category, OffreRepository $rep, PaginatorInterface $paginator, Request $req): Response
    {

        $offres = $rep->findByCategory($category);
        $pagination = $paginator->paginate(
            $offres,
            $req->query->get('page', 1),
            2


        );
        return $this->render('frontoffice/offre/listOffre.html.twig', [

            'pagination' => $pagination
        ]);
    }
}
