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


    
    

    #[Route('/donate', name: 'app_donate')]
    public function donate(Request $request, EntityManagerInterface $entityManager, UtiisateurRepository $userRepository): JsonResponse
    {
        // Get the logged-in user (donateur)
        $donateur = $this->getUser(); // Assuming you're using Symfony's built-in user authentication system
    
        // Create a new instance of the Donation entity
        $donation = new Dons();
    
        // Create the form for donation
        $form = $this->createForm(DonsType::class, $donation);
    
        // Handle form submission
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Create a new instance of Utilisateur
            $user = new Utilisateur();
    
            // Set user properties using setter methods
            $user->setNom('John');
            $user->setPrenom('Doe');
            $user->setEmail('john.doe@example.com');
            $user->setMdp('password'); // You should hash passwords for security
            $user->setDateInscription(new DateTime()); // Assuming you have imported DateTime class
            $user->setPhotoProfil('profile.jpg');
            $user->setRib(1234567890);
            $user->setAdresse('123 Main St, City');
            $user->setTel(123456789);
            $user->setNote(0);
            $user->setStatut('débutant');
            $user->setRole(['utilisateur']);
            $user->setIsActive(false);
            $user->setSalt("qosdhqiygdqgyz"); // You may set salt based on your application logic
    
            // Dump the Utilisateur object
            dump($user);
    
            // Set the Utilisateur object as the donateur for the donation
            $donation->setDonateur($user);
    
            // Set other donation data
            $donation->setMontant($form->get('montant')->getData());
            $donation->setOrganisation($form->get('organisation')->getData());
            $donation->setDate(new DateTime());
    
            // Persist and flush the donation entity
            $entityManager->persist($donation);
            $entityManager->flush();
    
            // Flash success message
            $this->addFlash('success', 'Donation submitted successfully.');
    
            // Redirect to donation listing page
            return $this->redirectToRoute('app_donation');
        }
    
        // If form is not submitted or invalid, retrieve and display form errors
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }
    
        // Return error message as JSON response
        return new JsonResponse(['error' => 'Error occurred while submitting the donation form.', 'errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
    }
}
