<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('frontoffice/home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    
  
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('frontoffice/home/aboutUs.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
   
}
