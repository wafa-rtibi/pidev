<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\ReclamationRepository\findByFilters;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use DateInterval;




use App\Repository\ReponseRepository;
use App\Service\BadWordsReclamationService;

class ReclamationController extends AbstractController
{
   
    
    //Ajouter Reclamation
    #[Route('/reclamation/add/{reclamateur_id}', name: 'app_reclamation')]
    public function add_reclamation(Request $request, $reclamateur_id, ManagerRegistry $doctrine, ReclamationRepository $reclamationRepository,BadWordsReclamationService $BadWordsReclamationService): Response
    {
        // Récupération de l'utilisateur
    $user = $doctrine->getRepository(Utilisateur::class)->find($reclamateur_id);

    // Vérification si l'utilisateur existe
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Création d'une nouvelle réclamation
    $dateActuelle = new DateTime();
    $complaint = new Reclamation();
    $complaint->setDateReclamation($dateActuelle);
    $complaint->setReclamateur($user);
    $complaint->setStatutReclamation('Sent successfully'); // Par défaut

    // Création du formulaire de réclamation
    $form = $this->createForm(ReclamationType::class, $complaint);

    // Gestion de la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Filtrage des mots inappropriés dans la description de la réclamation
        $descriptionEntree = $complaint->getDescriptionReclamation();
        $complaint->setDescriptionReclamation($BadWordsReclamationService->filterWords($descriptionEntree));

        // Enregistrement de la réclamation dans la base de données
        $em = $doctrine->getManager();
        $em->persist($complaint);
        $em->flush();

        // Redirection vers la liste des réclamations après l'ajout
        return $this->redirectToRoute('app_list', ['reclamateur_id' => $reclamateur_id]);
    }

    // Affichage du formulaire de réclamation
    return $this->render('frontoffice/reclamation/addReclamation.html.twig', [
        'form' => $form->createView(),
        'reclamateur_id' => $reclamateur_id,
        'complaints' => $reclamationRepository->findAll(),
    ]);
}
    
    
    //pour ajouter les reclamation avec ses info au dashboard admin
  /* #[Route('/reclamation/dashboard', name: 'app_dashboard_reclamation')]
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
            $statutReclamation=$reclamation->getStatutReclamation();

            $reponse=$reclamation->getReponse(); //najm nfasskhha

            // Ajoutez ces informations au tableau
            $tableauReclamations[] = [
                'id' => $id,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'dateReclamation' => $dateReclamation,
                'typeReclamation' => $typeReclamation,
                'descriptionReclamation' => $descriptionReclamation,
                'statutReclamation' => $statutReclamation,

                'reponse' => $reponse, //najm nfasskhha

            ];
        }

        // Rendre le dashborad
        return $this->render('frontoffice/reclamation/dashboard_reclamation.html.twig', [
            'tableauReclamations' => $tableauReclamations,
            'user'=>$user
        ]);
    }***/



    #[Route('/reclamation/dashboard', name: 'app_dashboard_reclamation')]
    public function dashboard_reclamation(Request $request): Response
    {
        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('q');

         // Récupérer le type de réclamation depuis la requête
         $typeReclamation = $request->query->get('type');

        // Récupérer toutes les réclamations
        $reclamations = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findAll();

        // Tableau pour stocker les informations à afficher
        $tableauReclamations = [];

        // Pour chaque réclamation, récupérez les informations nécessaires
        foreach ($reclamations as $reclamation) {
            $user = $reclamation->getReclamateur();
            $nom = $user->getNom();
            $prenom = $user->getPrenom();
            $email = $user->getEmail();
            $dateReclamation = $reclamation->getDateReclamation();
            $typeReclamationEntity = $reclamation->getType();
            $descriptionReclamation = $reclamation->getDescriptionReclamation();
            $statutReclamation=$reclamation->getStatutReclamation();

            $reponse=$reclamation->getReponse(); //najm nfasskhha


            // Vérifier si la réclamation correspond au terme de recherche
            if (
                stripos($nom, $searchTerm) !== false ||
                stripos($prenom, $searchTerm) !== false ||
                stripos($email, $searchTerm) !== false
            ) {

                if ($typeReclamation && $typeReclamationEntity === $typeReclamation) {
                    // Ajouter les informations au tableau si le type de réclamation correspond
                    $tableauReclamations[] = [
                        'id' => $reclamation->getId(),
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'dateReclamation' => $dateReclamation,
                        'typeReclamation' => $typeReclamationEntity,
                        // Autres informations de réclamation...
                        'descriptionReclamation' => $descriptionReclamation,
                'statutReclamation' => $statutReclamation,

                'reponse' => $reponse,
                    ];



                } elseif (!$typeReclamation) {
                    // Ajouter les informations au tableau si aucun type de réclamation n'est spécifié
                    $tableauReclamations[] = [
                        'id' => $reclamation->getId(),
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'dateReclamation' => $dateReclamation,
                        'typeReclamation' => $typeReclamationEntity,
                        // Autres informations de réclamation...
                        'descriptionReclamation' => $descriptionReclamation,
                'statutReclamation' => $statutReclamation,

                'reponse' => $reponse,
                    ];
                }
            }
        }
        // Rendre le dashboard avec les réclamations filtrées
        return $this->render('frontoffice/reclamation/dashboard_reclamation.html.twig', [
            'tableauReclamations' => $tableauReclamations,
            'user'=>$user,
            'typeReclamation' => $typeReclamation, // Renvoie le type de réclamation à la vue
            'searchTerm' => $searchTerm, // Renvoie le terme de recherche à la vue
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
        // return $this->render('frontoffice/reclamation/thankyou.html.twig', [
            return $this->render('frontoffice/reclamation/shouff.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
 
    



    //pour chaque utilisateur afficher une liste des ses reclamation
    //l'utilisateur peut modifier et supprimer sa réclamation uniquement dans un délai de 2 heures après son envo
  
    
  
  /*clamations/{reclamateur_id}', name: 'app_list')]
    public function listReclamations(Request $request, ManagerRegistry $doctrine, UtilisateurRepository $utilisateurRepository,int $reclamateur_id): Response
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
    }*/

    //afficher une reclamation pour admin
    #[Route('/admin/reclamation/{reclamateur_id}/{id}', name: 'app_reclamation_show_admin')]
    public function show_reclamation_admin($id,int $reclamateur_id,UtilisateurRepository $utilisateurRepository,ReclamationRepository $reclamationRepository): Response
    {   

        $user = $utilisateurRepository->find($reclamateur_id);
        $reclamation = $reclamationRepository->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('frontoffice/reclamation/show_reclam_admin.html.twig', [
            'reclamation' => $user,
            'reclam'=>$reclamation
        ]);
    }

    //show reclamation pour client 
    #[Route('/reclamation/{reclamateur_id}/{id}', name: 'app_reclamation_show')]
    public function show_reclamation( $id, $reclamateur_id,UtilisateurRepository $utilisateurRepository,ReclamationRepository $reclamationRepository): Response
     
    {    $user = $utilisateurRepository->find($reclamateur_id);
         $reclamation = $reclamationRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
       
        return $this->render('frontoffice/reclamation/show_reclam_client.html.twig', [
            'reclamation' => $user,
            'reclam'=>$reclamation
        ]);
    }




//bech narjaa nchoufha 


#[Route('/listReclamations/{reclamateur_id}', name: 'app_list')]
public function listReclamations(Request $request, ManagerRegistry $doctrine, UtilisateurRepository $utilisateurRepository, $reclamateur_id): Response
{
    $user = $utilisateurRepository->find($reclamateur_id);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    // Récupération de toutes les réclamations de l'utilisateur
    $reclamations = $user->getReclamations();

    // Filtrage des réclamations selon le type et le statut
    $typeFilter = $request->query->get('type');
    $statutFilter = $request->query->get('statut');

    $filteredReclamations = [];

    foreach ($reclamations as $reclamation) {
        // Vérification du type
        if ($typeFilter && $reclamation->getType() !== $typeFilter) {
            continue; // Passer à la prochaine réclamation si le type ne correspond pas
        }

        // Vérification du statut
        if ($statutFilter && $reclamation->getStatutReclamation() !== $statutFilter) {
            continue; // Passer à la prochaine réclamation si le statut ne correspond pas
        }

        // Si la réclamation passe les filtres, l'ajouter aux réclamations filtrées
        $filteredReclamations[] = $reclamation;
    }

    $currentTime = new DateTime();

    return $this->render('frontoffice/reclamation/list.html.twig', [
        'reclamations' => $filteredReclamations,
        'reclamateur_id' => $reclamateur_id,
        'user' => $user,
        'currentTime' => $currentTime,
    ]);
}

}
