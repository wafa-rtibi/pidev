<?php

// namespace App\Controller;

// use App\Form\CommentType;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\Request;
// use App\Entity\Blog; ///////////////////// Import Blog entity
// use App\Entity\Commentaire; ///////////////////// Import Blog entity
// use Symfony\Component\Security\Core\Security;
// use Symfony\Component\Security\Core\Exception\AccessDeniedException;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class CommentaireController extends AbstractController
// {



//     #[Route('/delete-commentfront/{id}', name: 'delete_commentfront')]
//     public function deleteCommentairefront(int $id, Request $request,Security $security): Response
//     {
//         $entityManager = $this->getDoctrine()->getManager();
//         $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);

//         if (!$commentaire) {
//             throw $this->createNotFoundException('Commentaire non trouvé avec l\'identifiant '.$id);
//         }

//         $user = $security->getUser();
//         $adminId = 3; 
//         if ($user && $user->getUserIdentifier() !== $adminId) {
//             throw new AccessDeniedException('Vous n\'avez pas le droit de supprimer ce commentaire.');
//         }

//         $commentaire->getIdblog()->removeCommentaire($commentaire);

//         $entityManager->remove($commentaire);
//         $entityManager->flush();

//         $referer = $request->headers->get('referer');
//         return new RedirectResponse($referer);
//     }




// #[Route('/add-commentfront/{id}', name: 'add_commentfront')]
// public function addCommentairefront($id, Request $request, Security $security): Response
// {
// $blog = $this->getDoctrine()->getRepository(Blog::class)->find($id);

// if (!$blog) {
//     throw $this->createNotFoundException('Blog non trouvé avec l\'identifiant '.$id);
// }

// $commentaire = new Commentaire();

// $form = $this->createForm(CommentType::class, $commentaire);

// $form->handleRequest($request);
// if ($form->isSubmitted() && $form->isValid()) {
    // Récupérer l'utilisateur connecté (avec l'ID 3)
    // $adminId = 3; // ID de l'admin statiquement défini à 3
    // $user = $this->getDoctrine()->getRepository(Admin::class)->find($adminId);
    // if (!$user) {
    //     throw $this->createNotFoundException('Admin non trouvé avec l\'identifiant '.$adminId);
    // }

    // $commentaire->setIdadmin($user);

    // $commentaire->setIdblog($blog);

//     $entityManager = $this->getDoctrine()->getManager();
//     $entityManager->persist($commentaire);
//     $entityManager->flush();

//      return $this->render('frontoffice/blog/single_blog.html.twig', ['id' => $commentaire->getIdblog()->getId()]);

// }

// return $this->render('blogfront/add.html.twig', [
//     'formadd' => $form->createView(),
// ]);
// }
// #[Route('/edit-commentfront/{id}', name: 'edit_commentfront')]
// public function editCommentairefront($id, Request $request, Security $security): Response
// {
// $entityManager = $this->getDoctrine()->getManager();

// $user = $security->getUser();

// Si l'utilisateur n'est pas connecté ou si son ID est différent de 3, redirigez-le ou faites une autre action appropriée
//  if (!$user || $user->getID() !== 3) {
    // Redirection, message d'erreur, etc.
   // throw $this->createAccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.');
// }

// $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);

// if (!$commentaire) {
//     throw $this->createNotFoundException('Commentaire non trouvé avec l\'identifiant '.$id);
// }

// $form = $this->createForm(CommentType::class, $commentaire);

// // Traiter le formulaire soumis
// $form->handleRequest($request);

// if ($form->isSubmitted() && $form->isValid()) {

//     $entityManager->flush();

//     return $this->render('frontoffice/blog/single_blog.html.twig', ['id' => $commentaire->getIdblog()->getId()]);
// }

// return $this->render('blogfront/add.html.twig', [
//     'formadd' => $form->createView(),
// ]);
// }
// }