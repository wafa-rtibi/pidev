<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrganisationRepository;
use App\Entity\Organisation;
use App\Form\DonsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\Recaptcha3Type;


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

    #[Route('/organisation/search', name: 'app_organisation_search')]
    public function search(Request $request, OrganisationRepository $organisationRepository): Response
    {
        $query = $request->query->get('query');

        // Rechercher des organisations en fonction de la requête
        $organisations = $organisationRepository->search($query);

        // Rendre le template avec les résultats de la recherche
        return $this->render('organisation/index.html.twig', [
            'organisations' => $organisations,
            'query' => $query,
        ]);
    }

    #[Route('/donate_to_organisation/{id}', name: 'app_donate_to_organisation')]
    public function donateToOrganisation(Request $request, Organisation $organisation): Response
    {
        $form = $this->createForm(DonsType::class)
            ->add('captcha', Recaptcha3Type::class, [ /* Ensure that 'Recaptcha3Type' is imported */
                // Add your Recaptcha3Type options here
            ]);

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
