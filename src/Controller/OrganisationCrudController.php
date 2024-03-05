<?php
/*
namespace App\Controller;

use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/organisation/crud')]
class OrganisationCrudController extends AbstractController
{
    
    #[Route('/', name: 'app_organisation_crud_index', methods: ['GET'])]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        return $this->render('backoffice/organisation/index.html.twig', [
            'organisations' => $organisationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_organisation_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organisation);
            $entityManager->flush();

            return $this->redirectToRoute('app_organisation_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/organisation/new.html.twig', [
            'organisation' => $organisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organisation_crud_show', methods: ['GET'])]
    public function show(Organisation $organisation): Response
    {
        return $this->render('backoffice/organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_organisation_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organisation $organisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organisation_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/organisation/edit.html.twig', [
            'organisation' => $organisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organisation_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Organisation $organisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organisation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($organisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organisation_crud_index', [], Response::HTTP_SEE_OTHER);
    }


    
}
*/

namespace App\Controller;

use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/organisation/crud')]
class OrganisationCrudController extends AbstractController
{
    
    #[Route('/', name: 'app_organisation_crud_index', methods: ['GET'])]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        return $this->render('backoffice/organisation/index.html.twig', [
            'organisations' => $organisationRepository->findAll(),
        ]);
    }

    #[Route('/showfront', name: 'app_organisation_showfront', methods: ['GET'])]
    public function showfront(OrganisationRepository $organisationRepository): Response
    {
        return $this->render('frontoffice/donation/donation.html.twig', [
            'organisations' => $organisationRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_organisation_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organisation);
            $entityManager->flush();

            return $this->redirectToRoute('app_organisation_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/organisation/new.html.twig', [
            'organisation' => $organisation,
            'form' => $form->createView(), // Création de la vue du formulaire
        ]);
    }

    #[Route('/{id}', name: 'app_organisation_crud_show', methods: ['GET'])]
    public function show(Organisation $organisation): Response
    {
        return $this->render('backoffice/organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_organisation_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organisation $organisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organisation_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backoffice/organisation/edit.html.twig', [
            'organisation' => $organisation,
            'form' => $form->createView(), // Création de la vue du formulaire
        ]);
    }

    #[Route('/{id}', name: 'app_organisation_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Organisation $organisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organisation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($organisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organisation_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
