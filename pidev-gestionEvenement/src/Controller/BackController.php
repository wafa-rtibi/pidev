<?php

namespace App\Controller;

use App\Entity\Type;
use App\Entity\Evenement;
use App\Repository\eventRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class BackofficeController extends AbstractController
{
    #[Route('/back', name: 'app_backoffice')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackofficeController',
        ]);
    }

    #[Route('/typeb', name: 'typeb')]
    public function typeb(EntityManagerInterface $entityManager): Response
    {
        $type = $entityManager
            ->getRepository(Type::class)
            ->findAll();
        return $this->render('back/type.html.twig', [
            'type' => $type,
            'controller_name' => 'BackofficeController',
        ]);
    }
    #[Route('/evenementb', name: 'evenementb')]
    public function evenementb(EntityManagerInterface $entityManager): Response
    {
        $evenement = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('back/evenement.html.twig', [
            'evenements' => $evenement,
            'controller_name' => 'BackofficeController',
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, eventRepository $eventRepository, Environment $twig): Response
    {
        $searchTerm = $request->request->get('searchTerm');
        $evenement = $eventRepository->search($searchTerm); // Appel à une méthode de recherche dans votre repository
        return new Response($twig->render('back/event_list.html.twig', ['evenements' => $evenement]));
    }
}
