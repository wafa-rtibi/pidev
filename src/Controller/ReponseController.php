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

use App\Service\NotificationService;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

//#[Route('/reponse')]
class ReponseController extends AbstractController
{ 

    //ajouter reponse tkhdem hmdlh
    //Ajouter une reponse pour une reclamation l'ajout se fait avec avec l'id de la reclamation
    #[Route('/reponse/addReponse/{id}', name: 'app_reponse_new')]
    public function addReponse(Request $request, EntityManagerInterface $entityManager, $id, ManagerRegistry $doctrine, NotificationService $notificationService): Response
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



               // Envoyer une notification au client
               $clientEmail = $user->getEmail(); // Remplacez cela par le champ approprié dans votre entité Utilisateur
               $reclamationId = $complaint->getId(); // Assurez-vous que votre entité Reclamation a une méthode getId() appropriée
               $notificationService->sendNotification($clientEmail, $reclamationId);

            
            return $this->redirectToRoute('app_reponse_done');
        }
    
        return $this->render('frontoffice/reponse/addReponse.html.twig', [
            'form' => $form->createView(),
            'complaint' => $complaint,
        ]);
    }

//controller jedid
//hne el admin ychouf reclamation o yaaml ajouter reponse f nafess l page
#[Route('/reclamation/show_and_add_response/{reclamateur_id}/{id}', name: 'app_reclamation_show_and_add_response')]
public function showAndAddResponse(Request $request, EntityManagerInterface $entityManager, $id, ManagerRegistry $doctrine, $reclamateur_id): Response {
    // Récupérer la réclamation en fonction de son identifiant
    $complaint = $doctrine->getRepository(Reclamation::class)->find($id);
    
    // Vérifier si la réclamation existe, sinon, déclencher une exception
    if (!$complaint) {
        throw $this->createNotFoundException('Complaint not found');
    }

    // Récupérer l'utilisateur associé à la réclamation
    $user = $complaint->getReclamateur();
    
    // Récupérer la date actuelle
    $dateActuelle = new DateTime();

    // Créer une nouvelle instance de réponse
    $reponse = new Reponse();
    $reponse->setDateReponse($dateActuelle);
    $reponse->setReclamReponse($complaint); 

    // Créer le formulaire pour ajouter une réponse
    $form = $this->createForm(ReponseType::class, $reponse);
    $form->handleRequest($request);

    // Vérifier si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Persister la réponse
        $entityManager->persist($reponse);
        $entityManager->flush();

        // Mettre à jour le statut de la réclamation
        $complaint->setStatutReclamation('Resolved');
        $entityManager->persist($complaint);
        $entityManager->flush();
        
        // Rediriger vers la page de confirmation de réponse
        return $this->redirectToRoute('app_reponse_done');
    }

    // Rendre la vue pour afficher le formulaire de réponse
    return $this->render('frontoffice/reclamation/show_and_add_response.html.twig', [
        'reclamation' => $complaint,
        'reclamateur_id' => $reclamateur_id,
        'form' => $form->createView(),
    ]);
}




    
    #[Route('/reponse/thankyou', name: 'app_reponse_done')]
    public function done_reponse(): Response
    {
        return $this->render('frontoffice/reponse/thankyou.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }


     //tkhdem jawha behy hmdlh
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
    

    //tkhdem b shyh jawha behy hmdlh
    // Pour afficher la reponse du reclamation au client
   
    #[Route('/reponse/show/{reclamateur_id}/{id}', name: 'app_reponse_show')]
public function show_reponse($id, $reclamateur_id, UtilisateurRepository $utilisateurRepository, ReponseRepository $reponseRepository, EntityManagerInterface $entityManager): Response
{  
    // Récupérer l'utilisateur associé à l'identifiant du reclamateur
    $user = $utilisateurRepository->find($reclamateur_id);

    // Récupérer la réponse associée à l'identifiant fourni
    $reponse = $reponseRepository->find($id);

    // Vérifier si la réponse existe
    if ($reponse) {
        // Marquer la réponse comme vue par l'utilisateur
        $reponse->setVu(true);
        $entityManager->flush(); // Enregistrer les changements dans la base de données
    } else {
        throw $this->createNotFoundException('Response not found'); // Lancer une exception si la réponse n'est pas trouvée
    }

    // Vérifier si l'utilisateur existe
    if (!$user) {
        throw $this->createNotFoundException('User not found'); // Lancer une exception si l'utilisateur n'est pas trouvé
    }

    // Rendre la vue Twig pour afficher la réponse
    return $this->render('frontoffice/reponse/show.html.twig', [
        'rep' => $reponse, // Passer la réponse à la vue
        'reponse' => $user, // Passer l'utilisateur à la vue
    ]);
}


    //tkhdem b shyh hmdlh
    //la modification du reponse se faire en utlisant l'id de la reponse
    //admin faire le modification du reponse
    //aprés que l'admin a modifier  reponse el peut la consulter 
    #[Route('/reponse/edit/{reclamateur_id}/{id}', name: 'app_reponse_edit')]
    public function edit_reponse(Request $request, ManagerRegistry $doctrine, int $id,$reclamateur_id): Response
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

            return $this->redirectToRoute('app_reponse_admin_show', ['reclamateur_id' =>$reclamateur_id,'id' => $reponse->getId()]);
        }

        return $this->render('frontoffice/reponse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


   
//supprimer une reponse de reclamation selon son id et  rediriger vers  la page d'acc
    #[Route('/reponse/delete/{id}', name: 'app_delete_reponse')]
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




//nhassen fel affichage

//pagination


 


