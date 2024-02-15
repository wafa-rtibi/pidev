<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function afficherOffre(): Response
    {
        return $this->render('frontoffice/offre/single_offre.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }
    #[Route('/offres', name: 'app_all_offre')]
    public function afficherAll(): Response
    {
        return $this->render('frontoffice/offre/category.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }
    #[Route('/offre', name: 'app_addOffre')]
    public function ajouterOffre(): Response
    {
        return $this->render('offre/single_offre.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }
    #[Route('/offre', name: 'app_updateOffre')]
    public function modifierOffre(): Response
    {
        return $this->render('offre/single_offre.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }
    #[Route('/offre', name: 'app_deleteOffre')]
    public function supprimerOffre(): Response
    {
        return $this->render('offre/single_offre.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }
}
