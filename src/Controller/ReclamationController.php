<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use DateInterval;


use App\Repository\ReponseRepository;

class ReclamationController extends AbstractController
{
   
    
    //Ajouter Reclamation
    #[Route('/reclamation/add/{reclamateur_id}', name: 'app_reclamation')]
    public function add_reclamation(Request $request, $reclamateur_id, ManagerRegistry $doctrine, ReclamationRepository $reclamationRepository): Response
    {
        $user = $doctrine->getRepository(Utilisateur::class)->find($reclamateur_id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $dateActuelle = new DateTime();
        $complaint = new Reclamation();
        $complaint->setDateReclamation($dateActuelle);
        $complaint->setReclamateur($user);
        //par defaut statut reclamation="Sent successfully"
        $complaint->setStatutReclamation('Sent successfully');
    
        $form = $this->createForm(ReclamationType::class, $complaint);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($complaint);
            $em->flush();
    
            //aprés l'ajout rediriger vers la liste des réclamations
            return $this->redirectToRoute('app_list', ['reclamateur_id' => $reclamateur_id]);
        }
    
        return $this->render('frontoffice/reclamation/addReclamation.html.twig', [
            'form' => $form->createView(),
            'reclamateur_id' => $reclamateur_id,
            'complaints' => $reclamationRepository->findAll(),
        ]);
    }
    
    
    //pour ajouter les reclamation avec ses info au dashboard admin
    #[Route('/reclamation/dashboard', name: 'app_dashboard_reclamation')]
    public function dashboard_reclamation(Request $request, ReclamationRepository $reclamationRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        // Récupérer toutes les réclamations
        $reclamations = $reclamationRepository->findAll();

        // Tableau pour stocker les informations à afficher
        $tableauReclamations = [];

        // Pour chaque réclamation, récupérez les informations nécessaires
        foreach ($reclamations as $reclamation) {
            $id= $reclamation->getId();
            $user = $reclamation->getReclamateur();
            $nom = $user->getNom();
            $prenom = $user->getPrenom();
            $email = $user->getEmail();
            $dateReclamation = $reclamation->getDateReclamation();
            $typeReclamation = $reclamation->getType();
            $descriptionReclamation = $reclamation->getDescriptionReclamation();

            // Ajoutez ces informations au tableau
            $tableauReclamations[] = [
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'dateReclamation' => $dateReclamation,
                'typeReclamation' => $typeReclamation,
                'descriptionReclamation' => $descriptionReclamation,
            ];
        }

        // Rendre le dashborad
        return $this->render('frontoffice/reclamation/dashboard_reclamation.html.twig', [
            'tableauReclamations' => $tableauReclamations,
        ]);
    }




    //Modifier reclamation 
    #[Route('/reclamation/edit/{reclamateur_id}/{id}', name: 'app_edit')]
    public function edit_reclamation(Request $request, ManagerRegistry $doctrine, int $id,int $reclamateur_id): Response
    {
        $entityManager = $doctrine->getManager();
        $reclamationRepository = $entityManager->getRepository(Reclamation::class);
        

        $reclamation = $reclamationRepository->find($id);
        
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_list', ['reclamateur_id' => $reclamateur_id]);

          
        }

        return $this->render('frontoffice/reclamation/edit.html.twig', [
            'form' => $form->createView(),
            'reclamateur_id' => $reclamateur_id,
            'id' => $id, 
            
        ]);
        
    }



   //supprimer reclamation 
   #[Route('/reclamation/delete/{reclamateur_id}/{id}', name: 'app_delete_reclamation')]
    public function delete_reclamation(ReclamationRepository $reclamationRepository, ManagerRegistry $doctrine,int $id,int $reclamateur_id): Response
    {
        $entityManager = $doctrine->getManager();
        
        $reclamation = $reclamationRepository->find($id);
        
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
       }

       $entityManager->remove($reclamation);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_list',['reclamateur_id' => $reclamateur_id]);
    }


  
// sayee
    #[Route('/reclamation/thankyou', name: 'app_reclamation_done')]
    public function done_reclamation(): Response
    {
        return $this->render('frontoffice/reclamation/thankyou.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
 
    



    //pour chaque utilisateur afficher une liste des ses reclamation
    //l'utilisateur peut modifier et supprimer sa réclamation uniquement dans un délai de 2 heures après son envo
    #[Route('/listReclamations/{reclamateur_id}', name: 'app_list')]
    public function listReclamations(ManagerRegistry $doctrine, UtilisateurRepository $utilisateurRepository,int $reclamateur_id): Response
    {
        $user = $utilisateurRepository->find($reclamateur_id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $currentTime = new DateTime();
        

        $reclamations = $user->getReclamations();

        return $this->render('frontoffice/reclamation/list.html.twig', [
            'reclamations' => $reclamations,
            'reclamateur_id' => $reclamateur_id,
            'user' => $user,
            'currentTime' => $currentTime, 
    
        ]);
    }

    //afficher une reclamation pour admin
    #[Route('/admin/reclamation/{id}', name: 'app_reclamation_show_admin')]
    public function show_reclamation_admin(Reclamation $reclamation): Response
    {
        return $this->render('frontoffice/reclamation/show_reclam_admin.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    //show reclamation pour client 
    #[Route('/reclamation/{reclamateur_id}/{id}', name: 'app_reclamation_show')]
    public function show_reclamation(Reclamation $reclamation): Response
    {
        return $this->render('frontoffice/reclamation/show_reclam_client.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

}
