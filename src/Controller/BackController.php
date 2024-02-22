<?php

namespace App\Controller;

use App\Entity\Type;
use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
}
