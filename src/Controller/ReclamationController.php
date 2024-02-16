<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;



class ReclamationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/ajouterreclam', name: 'app_reclamation')]
    public function complaint(Request $request): Response
    {
        $complaint = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $complaint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($complaint);
            $this->entityManager->flush();

           
            return $this->redirectToRoute('app_reclamation_done');
        }

        return $this->render('frontoffice/reclamation/addReclamation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
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

            return $this->redirectToRoute('app_list');
        }

        return $this->render('templates/frontoffice/reclamation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }





   #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(int $id, ReclamationRepository $reclamationRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $reclamation = $reclamationRepository->find($id);
        
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
       }

       $entityManager->remove($reclamation);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_reclamation');
    }


  

    #[Route('/thankyou', name: 'app_reclamation_done')]
    public function done(): Response
    {
        return $this->render('frontoffice/reclamation/thankyou.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
 
    #[Route('/listReclamations', name: 'app_list')]
    public function listReclamations(ManagerRegistry $doctrine): Response
    {
    
        $rec = $doctrine->getRepository(reclamation::class);
        $reclamations=$rec->findAll();

        return $this->render('frontoffice/reclamation/list.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }


}
