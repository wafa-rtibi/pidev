<?php

namespace App\Controller;

use App\Entity\DemandeOffre;
use App\Entity\Offre;
use App\Entity\Utilisateur;
use App\Repository\DemandeOffreRepository;
use App\Repository\UtilisateurRepository;
use DatePeriod;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandeController extends AbstractController
{

    //afficher les demandes reçu coresponds au mes offres
     //updating page

    #[Route('/demande/recu/{update}', name: 'app_demande_recu')]

    public function afficherDemandeRecu(UtilisateurRepository $repo, DemandeOffreRepository $rep,$update,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $current_user = $repo->findOneByEmail($email);

        $offres = $current_user->getOffres();

        $demandes_recu = [];

        foreach ($offres as $offre) {
            $demandes_recu = array_merge($demandes_recu, $rep->findByOffre($offre->getId()));
        }
        if($update=="true"){
            foreach ($demandes_recu as $demande) {
                if($demande->getStatut() == 'refusé' ) {
                    unset($demandes_recu[array_search($demande, $demandes_recu)]);
                    $em=$doctrine->getManager();
                    $em->remove($demande);
                    $em->flush();

                    
            }}

        }

        return $this->render('frontoffice/offre/demandeRecuList.html.twig', [
            'demandesRecu' => $demandes_recu,
        ]);
    }

    //afficher les demandes envoyé par user connecté 


    #[Route('/demande/envoye/{update}', name: 'app_demande_envoye')]

    public function afficherDemandeEnvoye(UtilisateurRepository $repo, DemandeOffreRepository $rep,$update,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $current_user = $repo->findOneByEmail($email);

        $demandes = $current_user->getDemandeOffres();
        $demandes= $demandes->toArray();
        $message="";
        if($update=="true"){
            foreach ($demandes as $demande) {
                if($demande->getStatut() == 'refusé' ) {
                    unset($demandes[array_search($demande,$demandes)]);
                    $em=$doctrine->getManager();
                    $em->remove($demande);
                    $em->flush();

            }}
        }
        return $this->render('frontoffice/offre/demandeEnvoyeList.html.twig', [
            'demansesEnvoye' => $demandes,
            
        ]);
    }


    //accepter + demande declanche une messagerie + afficher leur etat de la liste des demandes recu  + offre twali reservé

    #[Route('/demande/accepte/{id_demande}', name: 'app_demande_accepte')]

    public function accepteDemande($id_demande, ManagerRegistry $doctrine): Response
    {

        $demande = $doctrine->getRepository(DemandeOffre::class)->find($id_demande);
        $demandeur = $doctrine->getRepository(Utilisateur::class)->find($demande->getDemandeur());
        $offre = $demande->getOffre();
        $offre->setEtat("reservé");
        $demande->setStatut("accepté");



        return $this->render('frontoffice/offre/demandeRecuList', [
            'demande' => $demande,
            'offre' => $offre,

        ]);
    }


    //refuser demande  

    #[Route('/demande/refus/{id_demande}', name: 'app_demande_refus')]

    public function refuseDemande($id_demande, ManagerRegistry $doctrine): Response
    {

        $demande = $doctrine->getRepository(DemandeOffre::class)->find($id_demande);
        $offre = $demande->getOffre();
        $demande->setStatut("refusé");

        return $this->render('frontoffice/offre/demandeRecuList', [
            'demande' => $demande,
            'offre' => $offre,

        ]);
    }

    //envoyer demande


    #[Route('/demande/send/{id_offre}', name: 'app_demande_send')]

    public function sendDemande($id_offre, ManagerRegistry $doctrine, UtilisateurRepository $rep,DemandeOffreRepository $repo): Response
    {
        $demande = new DemandeOffre();
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }
        $offre = $doctrine->getRepository(Offre::class)->find($id_offre);
        $demandeur = $rep->findOneByEmail($email);
     
       
              //l'ajout 
        $demande->setStatut('en attende');
        $demande->setDateCreation(new DateTime());
        $demande->setOffre($offre);
        $demande->setDemandeur($demandeur);
        $em = $doctrine->getManager();
        $em->persist($demande);
        $em->flush();

        $demandeur->addDemandeOffre($demande);
       
        
      


        return $this->render('frontoffice/offre/demandeEnvoyeList.html.twig', [
            'demandes' => $demande,
            'offre' => $offre,]);
    }

   
}