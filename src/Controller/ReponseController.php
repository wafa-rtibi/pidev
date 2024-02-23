<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
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

#[Route('/reponse')]
class ReponseController extends AbstractController
{   
    


    //Ajouter une reponse pour une reclamation l'ajout se fait avec avec l'id de la reclamation
    #[Route('/reponse/addReponse/{id}', name: 'app_reponse_new')]
    public function addReponse(Request $request, EntityManagerInterface $entityManager, $id, ManagerRegistry $doctrine): Response
    {
        $complaint = $doctrine->getRepository(Reclamation::class)->find($id);
        if (!$complaint) {
            throw $this->createNotFoundException('Complaint not found');
        }
    
        $user = $complaint->getReclamateur();
    
        $dateActuelle = new DateTime();
    
        $reponse = new Reponse();
        $reponse->setDateReponse($dateActuelle);
        $reponse->setReclamReponse($complaint); 
    
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();
            

            // Mettre à jour le statut de la réclamation
            $complaint->setStatutReclamation('Resolved');
            $entityManager->persist($complaint);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_reponse_done');
        }
    
        return $this->render('frontoffice/reponse/addReponse.html.twig', [
            'form' => $form->createView(),
            'complaint' => $complaint,
        ]);
    }



    
    #[Route('/reponse/thankyou', name: 'app_reponse_done')]
    public function done_reponse(): Response
    {
        return $this->render('frontoffice/reponse/thankyou.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }



    // Pour afficher la reponse du reclamation au admin
    //delon id de la reponse
    #[Route('/reponse/admin/show/{reclamateur_id}/{id}', name: 'app_reponse_admin_show')]
    public function show_reponse_admin($id, $reclamateur_id,UtilisateurRepository $utilisateurRepository,ReponseRepository $reponseRepository): Response
    {

        $user = $utilisateurRepository->find($reclamateur_id);

        $reponse = $reponseRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('frontoffice/reponse/show_reponse_admin.html.twig', [
            'rep' => $reponse,
            'reponse' => $user,
            
        ]);
    }
    
    // Pour afficher la reponse du reclamation au client
    //selon l'id de la reponse
    #[Route('/reponse/show/{reclamateur_id}/{id}', name: 'app_reponse_show')]
    public function show_reponse($id, $reclamateur_id,UtilisateurRepository $utilisateurRepository,ReponseRepository $reponseRepository): Response
    {  
        $user = $utilisateurRepository->find($reclamateur_id);

        $reponse = $reponseRepository->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('frontoffice/reponse/show.html.twig', [
            'rep' => $reponse,
            'reponse' => $user,
            
        ]);
    }
     
    //la modification du reponse se faire en utlisant l'id de la reponse
    //admin faire le modification du reponse
    //aprés que l'admin a modifier  reponse el peut la consulter 
    #[Route('/reponse/edit/{id}', name: 'app_reponse_edit')]
    public function edit_reponse(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $reponseRepository = $entityManager->getRepository(Reponse::class);
        
        $reponse = $reponseRepository->find($id);
        
        if (!$reponse) {
            throw $this->createNotFoundException('Reponse not found');
        }

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_admin_show', ['id' => $reponse->getId()]);
        }

        return $this->render('frontoffice/reponse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


   
//supprimer une reponse de reclamation selon son id et  rediriger vers  la page d'acc
    #[Route('/reponse/delete/{id}', name: 'app_delete')]
    public function delete_reponse(int $id, ReponseRepository $reponseRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $reponse = $reponseRepository->find($id);
        
        if (!$reponse) {
            throw $this->createNotFoundException('reponse not found');
       }

       $entityManager->remove($reponse);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_dashboard_reclamation');
   
   
    }
}

