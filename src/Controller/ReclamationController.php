<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function ajouterReclamation(): Response
    {
        return $this->render('frontoffice/reclamation/addReclamation.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/thankyou', name: 'app_reclamation_done')]
    public function done(): Response
    {
        return $this->render('frontoffice/reclamation/thankyou.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
}
