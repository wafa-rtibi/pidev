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
use Knp\Component\Pager\PaginatorInterface;




use App\Repository\ReponseRepository;
use App\Service\BadWordsReclamationService;


//Mailer
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;



use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ReclamationController extends AbstractController
{
   
    //Ajouter Reclamation
    #[Route('/reclamation/add/{reclamateur_id}', name: 'app_reclamation')]
    public function add_reclamation(Request $request, $reclamateur_id, ManagerRegistry $doctrine, ReclamationRepository $reclamationRepository,BadWordsReclamationService $BadWordsReclamationService,MailerInterface $mailer,FlashBagInterface $flashBag): Response
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
       
     
      //l'envoi d'un email 
        $email = (new Email())
        ->from(new Address ('batoutbata5@gmail.com' ,"Troky"))
        ->to($user->getEmail())
        ->subject('New complaint assigned')
        ->text('A new complaint has been assigned to your account. We will respond to you as soon as possible');

         $mailer->send($email);

         $flashBag->add('success', [
            'message' => 'Your complaint has been registered in our system',
            'timeout' => 8000, // Duree
        ]);
          

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
        
        // get les images  
        $image1 = $reclamation->getImage1();
        $image2 = $reclamation->getImage2();

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



//show reclamation pour client 
#[Route('/reclamation/{reclamateur_id}/{id}', name: 'app_reclamation_show')]
public function show_reclamation( $id, $reclamateur_id,UtilisateurRepository $utilisateurRepository,ReclamationRepository $reclamationRepository,PaginatorInterface $paginator, Request $req): Response
 
{    $user = $utilisateurRepository->find($reclamateur_id);
     $reclamation = $reclamationRepository->find($id);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }
   
    return $this->render('frontoffice/reclamation/show_reclam_client.html.twig', [
        'reclamation' => $user,
        'reclam'=>$reclamation,
       
    ]);
}


  //pour chaque utilisateur afficher une liste des ses reclamation
    //l'utilisateur peut modifier et supprimer sa réclamation uniquement dans un délai de 2 heures après son envoi
    #[Route('/listReclamations/{reclamateur_id}', name: 'app_list')]

    public function listReclamations(Request $request, ManagerRegistry $doctrine, UtilisateurRepository $utilisateurRepository, int $reclamateur_id,ReclamationRepository $rep,PaginatorInterface $paginator): Response
    {
        $user = $utilisateurRepository->find($reclamateur_id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $currentTime = new DateTime();
    
        // Récupérer les paramètres de recherche et de filtrage depuis la requête GET
        $date = $request->query->get('date');
        $statut = $request->query->get('statut');
    
        // Appeler la méthode correspondante du repository pour obtenir les réclamations
        $reclamations = $rep->findByDateAndStatut($reclamateur_id, $date, $statut);
    

        $pagination = $paginator->paginate(
            $reclamations,
            $request->query->get('page', 1), //page 1 par defaut
            4,
        );
        return $this->render('frontoffice/reclamation/list.html.twig', [
            //'reclamations' => $reclamations,
            'reclamations' =>  $pagination,
            'reclamateur_id' => $reclamateur_id,
            'user' => $user,
            'currentTime' => $currentTime,
        ]);
    }
    
    
    

//pour afficher les reclamations avec au dashboard admin
#[Route('/reclamation/dashboard', name: 'app_dashboard_reclamation')]
public function dashboard_reclamation( Request $request, ReclamationRepository $reclamationRepository, UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator): Response
{
    // Récupérer le terme de recherche à partir de la requête GET
    $searchTerm = $request->query->get('q');

    // Récupérer le type de réclamation à partir de la requête GET
    $typeReclamation = $request->query->get('type');

    // Récupérer l'ordre de tri à partir de la requête GET, avec une valeur par défaut 'asc'
    $sort = $request->query->get('sort', 'asc');

    // Appeler la méthode findByCriteria du ReclamationRepository pour récupérer les réclamations en fonction des critères
    $reclamations = $reclamationRepository->findByCriteria($searchTerm, $typeReclamation, $sort);
    $pagination = $paginator->paginate(
        $reclamations,
        $request->query->get('page', 1), //page 1 par defaut
        4,
    );
    return $this->render('backoffice/reclamation/dashboard_reclamation.html.twig', [
        'tableauReclamations' => $pagination,
        'user' => $this->getUser()
    ]);
}


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



    #[Route('/reclamation/statistics', name: 'complaint_statistics')]
    public function complaintStatistics(ReclamationRepository $reclamationRepository): Response
    {
        // Récupération des statistiques par type de réclamation
        $statistics = $reclamationRepository->getReclamationStatisticsByType();
    
        // Configuration des données du graphique
        $chartData = [
            'labels' => [], // Les labels des types de réclamations
            'datasets' => [
                [
                    'label' => 'Nombre de réclamations par type',
                    'data' => [], // Le nombre de réclamations par type


                    'backgroundColor' => ['#6a6a6a','#ffc107','#198754'], // Couleurs de fond des barres
                    'borderColor' => ['#6a6a6a','#ffc107','#198754'], // Couleurs des bordures des barres
                    'borderWidth' => 1, // Largeur des bordures
                    
                ],
            ],
        ];
    
        // Remplissage des données du graphique
        foreach ($statistics as $type => $count) {
            $chartData['labels'][] = $type;
            $chartData['datasets'][0]['data'][] = $count;
        }
    
        // Rendu de la vue avec les données du graphique
        return $this->render('/backoffice/reclamation/statistics.html.twig', [
            'chartData' => json_encode($chartData),
        ]);
    }
    
    
   


 }
   

