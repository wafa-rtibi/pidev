<?php
/*
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
} */



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dons;
use App\Form\DonsType;
use App\Repository\DonsRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DonationController extends AbstractController
{
    #[Route('/donation', name: 'app_donation')]
    public function index(): Response
    {
        return $this->render('./frontoffice/donation/index.html.twig', [
            'controller_name' => 'DonationController',
        ]);
    }




    #[Route('/Affiche', name: 'app_donation_afficher')]
    public function Affiche (DonsRepository $repository)
        {
            $dons=$repository->findAll() ; //select *
            return $this->render('./frontoffice/dons/affiche.html.twig',['dons'=>$dons]);
        }


    #[Route('/add-donation', name: 'app_donation_ajouter')]
    public function  Add (Request  $request)
    {
        $dons=new Dons();
        $form =$this->CreateForm(DonsType::class,$dons);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($dons);
            $em->flush();
            return $this->redirectToRoute('app_Affiche');
        }
        return $this->render('./backoffice/dons/add.html.twig',['f'=>$form->createView()]);

    }


    #[Route('/edit/{id}', name: 'app_dons_edit')]
    public function edit(DonsRepository $repository, $id, Request $request)
    {
        $dons = $repository->find($id);
        $form = $this->createForm(DonsType::class, $dons);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_Affiche");
        }

        return $this->render('./backoffice/dons/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    
    #[Route('/delete/{id}', name: 'app_dons_delete')]
    public function delete($id, DonsRepository $repository)
    {
        $dons = $repository->find($id);

        if (!$dons) {
            throw $this->createNotFoundException('don non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($dons);
        $em->flush();

        
        return $this->redirectToRoute('app_dons_afficher');
    }

    
}

