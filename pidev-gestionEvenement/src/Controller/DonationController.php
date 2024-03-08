<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonationController extends AbstractController
{
    #[Route('/donation', name: 'app_donation')]
    public function index(): Response
    {
        return $this->render('frontoffice/donation/donation.html.twig', [
            'controller_name' => 'DonationController',
        ]);
    }
}
