<?php

namespace App\Controller;

use App\Entity\Evenement;

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



use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

//les use pour pdf
use Dompdf\Dompdf;
use Dompdf\Options;

//#[Route('/reponse')]
class ReponseController extends AbstractController
{ 

    //Ajouter une reponse pour une reclamation l'ajout se fait avec avec l'id de la reclamation
    #[Route('/reponse/addReponse/{id}', name: 'app_reponse_new')]
    public function addReponse(Request $request, EntityManagerInterface $entityManager, $id, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        // Récupération de la réclamation en fonction de l'ID fourni dans l'URL
        $complaint = $doctrine->getRepository(Reclamation::class)->find($id);
        if (!$complaint) {
            throw $this->createNotFoundException('Complaint not found');
        }
    
        // Récupération de l'utilisateur ayant soumis la réclamation
        $user = $complaint->getReclamateur();
    
        // Création d'un objet DateTime pour la date actuelle
        $dateActuelle = new DateTime();
    
        // Création d'un nouvel objet Reponse
        $reponse = new Reponse();
        $reponse->setDateReponse($dateActuelle);
        $reponse->setReclamReponse($complaint);
    
        // Création du formulaire de réponse à la réclamation
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
    
        // Vérification si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement de la réponse dans la base de données
            $entityManager->persist($reponse);
            $entityManager->flush();
    
            // Envoi d'un e-mail de notification à l'utilisateur
            $email = (new Email())
                ->from(new Address ('batoutbata5@gmail.com' ,"Troky"))
                ->to($user->getEmail())
                ->subject(' Regarding Your Complaint - Response from Troky ')
                ->text('We hope this email finds you well. We want to inform you that we have processed your complaint regarding');
    
            $mailer->send($email);
    
            // Mise à jour du statut de la réclamation à "Résolu"
            $complaint->setStatutReclamation('Resolved');
            $entityManager->persist($complaint);
            $entityManager->flush();
    
            // Redirection vers une autre page après soumission de la réponse
            return $this->redirectToRoute('app_reponse_done');
        }
    
        // Rendu du formulaire de réponse à la réclamation
        return $this->render('frontoffice/reponse/addReponse.html.twig', [
            'form' => $form->createView(),
            'complaint' => $complaint,
        ]);
    }
    

//controller jedid
//hne el admin ychouf reclamation o yaaml ajouter reponse f nafess l page
#[Route('/reclamation/show_and_add_response/{reclamateur_id}/{id}', name: 'app_reclamation_show_and_add_response')]
public function showAndAddResponse(Request $request, EntityManagerInterface $entityManager, $id, ManagerRegistry $doctrine, $reclamateur_id,MailerInterface $mailer): Response {
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

   // $reponse->setAdmin($admin); // Associer l'administrateur à la réponse


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


         // Envoi d'un e-mail de notification à l'utilisateur
         $email = (new Email())
         ->from(new Address ('batoutbata5@gmail.com' ,"Troky"))
         ->to($user->getEmail())
         ->subject(' Regarding Your Complaint - Response from Troky ')
         ->text('We hope this email finds you well. We want to inform you that we have processed your complaint regarding');

        $mailer->send($email);
        
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


     
    // afficher la reponse du reclamation au admin
    //selon id de la reponse
    #[Route('/reponse/admin/show/{reclamateur_id}/{id}', name: 'app_reponse_admin_show')]
public function show_reponse_admin($id, $reclamateur_id, UtilisateurRepository $utilisateurRepository, ReponseRepository $reponseRepository): Response
{
    // Récupération de l'utilisateur concerné par l'identifiant
    $user = $utilisateurRepository->find($reclamateur_id);

    // Récupération de la réponse par son identifiant
    $reponse = $reponseRepository->find($id);

    // Vérification si l'utilisateur existe
    if (!$user) {
        // Lancer une exception si l'utilisateur n'est pas trouvé
        throw $this->createNotFoundException('User not found');
    }

    // Rendu de la vue avec les données de la réponse et de l'utilisateur
    return $this->render('frontoffice/reponse/show_reponse_admin.html.twig', [
        'rep' => $reponse,  
        'reponse' => $user, 
        'reclamateur_id' =>$reclamateur_id
    ]);
}

    
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
        'rep' => $reponse, 
        'reponse' => $user, 
    ]);
}

    //la modification du reponse se faire en utlisant l'id de la reponse
    //admin faire le modification du reponse
    //aprés que l'admin a modifier  reponse el peut la consulter 
    //si client a  deja vu la reponse l'admin ne peut pas modifier ou supprimer cette reponse
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







