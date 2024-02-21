<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\OrganisationRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrganisationController extends AbstractController
{
    #[Route('/organisation', name: 'app_organisation')]
    public function index(): Response
    {
        return $this->render('./frontoffice/organisation/index.html.twig', [
            'controller_name' => 'OrganisationController',
        ]);
    }




    #[Route('/Affiche', name: 'app_organisation_afficher')]
    public function Affiche (OrganisationRepository $repository)
        {
            $organisations=$repository->findAll() ; //select *
            return $this->render('./frontoffice/organisation/affiche.html.twig',['organisations'=>$organisations]);
        }


    #[Route('/add-organisation', name: 'app_organisation_ajouter')]
    public function  Add (Request  $request)
    {
        $organisations=new Organisation();
        $form =$this->CreateForm(OrganisationType::class,$organisations);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($organisations);
            $em->flush();
            return $this->redirectToRoute('app_Affiche');
        }
        return $this->render('./backoffice/organisation/add.html.twig',['f'=>$form->createView()]);

    }


    #[Route('/edit/{id}', name: 'app_organisation_edit')]
    public function edit(OrganisationRepository $repository, $id, Request $request)
    {
        $organisations = $repository->find($id);
        $form = $this->createForm(OrganisationType::class, $organisations);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_Affiche");
        }

        return $this->render('./backoffice/organisation/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    
    #[Route('/delete/{id}', name: 'app_organisation_delete')]
    public function delete($id, OrganisationRepository $repository)
    {
        $organisations = $repository->find($id);

        if (!$organisations) {
            throw $this->createNotFoundException('organisations non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($organisations);
        $em->flush();

        
        return $this->redirectToRoute('app_organisation_afficher');
    }

    
}
