<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrganisationRepository;
use App\Entity\Organisation;
use App\Form\DonsType;
use Symfony\Component\HttpFoundation\Request;


class OrganisationController extends AbstractController
{
    #[Route('/organisation', name: 'app_organisation')]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        // Fetch all organisations from the repository
        $organisations = $organisationRepository->findAll();

        // Render the template and pass the organisations to it
        return $this->render('organisation/index.html.twig', [
            'controller_name' => 'OrganisationController',
            'organisations' => $organisations,
        ]);
    }

    #[Route('/donate_to_organisation/{id}', name: 'app_donate_to_organisation')]
    public function donateToOrganisation(Request $request, Organisation $organisation): Response
    {
        $form = $this->createForm(DonsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process the donation, save to database, etc.

            $this->addFlash('success', 'Thank you for your donation!');
            return $this->redirectToRoute('app_organisation');
        }

        return $this->render('organisation/donation_form.twig', [
            'organisation' => $organisation,
            'form' => $form->createView(),
        ]);
    }
}