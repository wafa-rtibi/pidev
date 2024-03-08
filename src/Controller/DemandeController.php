<?php

namespace App\Controller;

use App\Entity\DemandeOffre;
use App\Entity\Offre;
use App\Entity\Utilisateur;
use App\Repository\DemandeOffreRepository;
use App\Repository\UtilisateurRepository;
use App\Service\NotificationManager;
use DatePeriod;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandeController extends AbstractController
{

    //afficher les demandes reçu coresponds au mes offres
    //updating page

    #[Route('/demande/recu/{update}', name: 'app_demande_recu')]

    public function afficherDemandeRecu(UtilisateurRepository $repo, DemandeOffreRepository $rep, $update, ManagerRegistry $doctrine): Response
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
        if ($update == "true") {
            foreach ($demandes_recu as $demande) {
                if ($demande->getStatut() == 'refusé') {
                    unset($demandes_recu[array_search($demande, $demandes_recu)]);
                    $em = $doctrine->getManager();
                    $em->remove($demande);
                    $em->flush();
                }
            }
        }

        return $this->render('frontoffice/offre/demandeRecuList.html.twig', [
            'demandesRecu' => $demandes_recu,
        ]);
    }

    //afficher les demandes envoyé par user connecté 


    #[Route('/demande/envoye/{update}', name: 'app_demande_envoye')]

    public function afficherDemandeEnvoye(UtilisateurRepository $repo, DemandeOffreRepository $rep, $update, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $current_user = $repo->findOneByEmail($email);

        $demandes = $current_user->getDemandeOffres();
        $demandes = $demandes->toArray();
        $message = "";
        if ($update == "true") {
            foreach ($demandes as $demande) {
                if ($demande->getStatut() == 'refusé') {
                    unset($demandes[array_search($demande, $demandes)]);
                    $em = $doctrine->getManager();
                    $em->remove($demande);
                    $em->flush();
                }
            }
        }
        return $this->render('frontoffice/offre/demandeEnvoyeList.html.twig', [
            'demansesEnvoye' => $demandes,

        ]);
    }


    //accepter + demande declanche une messagerie + afficher leur etat de la liste des demandes recu  + offre twali reservé + refusé les autres demande + offre oppossite twali reservé

    #[Route('/demande/accepte/{id_demande}', name: 'app_demande_accepte')]

    public function accepteDemande($id_demande, ManagerRegistry $doctrine, DemandeOffreRepository $rep, UtilisateurRepository $repo): Response
    {


        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $current_user = $repo->findOneByEmail($email);

        //tabdil
        $demande = $doctrine->getRepository(DemandeOffre::class)->find($id_demande);
        $demande->setStatut("accepté");
        $em = $doctrine->getManager();
        $em->persist($demande);
        $em->flush();
        
        $opposite=$demande->getOpposite();
        $opposite->setEtat("reservé");
        $offre = $demande->getOffre();
        $offre->setEtat("reservé");

        $autres_demandes=$offre->getDemandes();

        // mettre les autres demandes de cette offre refusées automatiquement
        foreach ($autres_demandes as $autre) {
            if($autre != $demande){
                  $autre->setStatut("refusé");
                  $em->persist($autre);
                  $em->flush();

            }
        }
        
        $em->persist($opposite);
        $em->persist($offre);
        $em->flush();

        $offres = $current_user->getOffres();

        $demandes_recu = [];

        foreach ($offres as $offre) {
            $demandes_recu = array_merge($demandes_recu, $rep->findByOffre($offre->getId()));
        }


        return $this->render('frontoffice/offre/demandeRecuList.html.twig', [
            'demandesRecu' =>  $demandes_recu,
            // 'offre' => $offre,

        ]);
    }


    //refuser demande  

    #[Route('/demande/refus/{id_demande}', name: 'app_demande_refus')]

    public function refuseDemande($id_demande, ManagerRegistry $doctrine, DemandeOffreRepository $rep, UtilisateurRepository $repo): Response
    {


        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }

        $current_user = $repo->findOneByEmail($email);

        //tabdil
        $demande = $doctrine->getRepository(DemandeOffre::class)->find($id_demande);
        $demande->setStatut("refusé");
        $em = $doctrine->getManager();
        $em->remove($demande);
        $em->flush();

        $offre = $demande->getOffre();
        $offre->setEtat("reservé");

        $offres = $current_user->getOffres();

        $demandes_recu = [];

        foreach ($offres as $offre) {
            $demandes_recu = array_merge($demandes_recu, $rep->findByOffre($offre->getId()));
        }


        return $this->render('frontoffice/offre/demandeRecuList.html.twig', [
            'demandesRecu' =>  $demandes_recu,
            // 'offre' => $offre,

        ]);
    }

    //envoyer demande


    #[Route('/demande/send/{id_offre}', name: 'app_demande_send')]

    public function sendDemande($id_offre, ManagerRegistry $doctrine, UtilisateurRepository $rep,Request $req,NotificationManager $notificationManager): Response
    {
        $date = new DateTime();
        $demande = new DemandeOffre();
        $user = $this->getUser();
        $message=$req->query->get('message');
        $id_opposite=$req->query->get('selectedOffer');


        if ($user) {

            $email = $user->getUserIdentifier();
        }
        
        $offre = $doctrine->getRepository(Offre::class)->find($id_offre);
        $opposite = $doctrine->getRepository(Offre::class)->find($id_opposite);
        $demandeur = $rep->findOneByEmail($email);

    
        //l'ajout 
        $demande->setStatut('en attende');
        $demande->setDateCreation(new DateTime());
        $demande->setOffre($offre);
        $demande->setOpposite($opposite);
        $demande->setDemandeur($demandeur);
        $demande->setMessage($message);
        $em = $doctrine->getManager();
        $em->persist($demande);
        $em->flush();

        $demandeur->addDemandeOffre($demande);


        $message = "Vous avez reçu une nouvelle demande de " . $demandeur->getNom() ."pour l'offre" .$offre->getNom();

        // Appel du service de notification pour envoyer la notification
        $notificationManager->sendNotification($offre->getOffreur() , $message);



        return $this->render('frontoffice/offre/demandeEnvoyeList.html.twig', [
            'demandes' => $demande,
            'offre' => $offre,
            'date' => $date
        ]);
    }

    //demandes recus d'une seule offre
    #[Route('/demande/{id_offre}', name: 'app_demande_offre')]
    public function afficherDemandeOffre($id_offre, DemandeOffreRepository $rep, ManagerRegistry $doctrine): Response
    {

        $offre = $doctrine->getRepository(Offre::class)->find($id_offre);

       $demandes_recu = $rep->findByOffre($offre->getId());
    
        return $this->render('frontoffice/offre/demandesOffre.html.twig', [
            'demandesRecu' => $demandes_recu,
            'offre'=>$offre,
        ]);
    }

      //suprimme mes demande si en attende 
      #[Route('/remove/demande/{id_demande}', name: 'app_demande_remove')]
      public function removeDemande($id_demande, DemandeOffreRepository $rep, UtilisateurRepository $repo, DemandeOffreRepository $repi, ManagerRegistry $doctrine): Response
      {
        $user = $this->getUser();

        if ($user) {

            $email = $user->getUserIdentifier();
        }


        $current_user = $repo->findOneByEmail($email);
        $demande_supprime=$doctrine->getRepository(DemandeOffre::class)->find($id_demande);
      
        $em=$doctrine->getManager();
        if($demande_supprime->getStatut() != 'accepté' && $demande_supprime->getStatut() != null ){
        $current_user->removeDemandeOffre($demande_supprime);
        $em->remove($demande_supprime);
        $em->flush();}
        $demandes = $current_user->getDemandeOffres();
        $demandes = $demandes->toArray();
      
      
        return $this->render('frontoffice/offre/demandeEnvoyeList.html.twig', [
            'demansesEnvoye' => $demandes,

        ]);
         
      }

}
