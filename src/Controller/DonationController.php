<?php

namespace App\Controller;

use App\Entity\Dons;
use App\Form\DonsType;
use App\Repository\UtiisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use App\Entity\Utilisateur;
use App\Repository\OrganisationRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\JsonResponse;


class DonationController extends AbstractController
{
    #[Route('/donation', name: 'app_donation')]
    public function index(DonsRepository $donsRepository): Response
    {
        $donations = $donsRepository->findAll();

        return $this->render('frontoffice/donation/donation.html.twig', [
            'donations' => $donations,
        ]);
    }


    
    

    #[Route('/donate/{id}', name: 'app_donate')]
    public function donate(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $userRepository, OrganisationRepository $orgr, $id): Response
    {
        $user = $userRepository->findOneBy(['nom' => 'Souhail']);
        $organisation = $orgr->findOneBy(['id' => $id]);
    
        // Create a new instance of the Donation entity
        $donation = new Dons();
        $donation->setDonateur($user); // Assuming the relation is correctly set up in your entity
        $donation->setOrganisation($organisation); // Set the organisation
        $donation->setDate(new DateTime()); // Set the current date
    
        // Create the form for donation
        $form = $this->createForm(DonsType::class, $donation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist and flush inside this block
            $entityManager->persist($donation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Donation submitted successfully.');
    
            // Redirect to donation listing page
            return $this->redirectToRoute('app_donation');
        }
    
        // Render the form view when first accessing the page or if the form is not submitted or not valid
        return $this->render('organisation/donation_form.twig', [
            'form' => $form->createView(),
            'organisation' => $organisation // Pass the organisation to the view for additional context if needed
        ]);
    }
    
}

