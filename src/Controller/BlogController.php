<?php

namespace App\Controller;
use DateTime;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\BlogRepository;
use App\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Form\BlogFormType;
use App\Entity\Blog; // Import your BlogPost entity
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\File;
use App\Entity\Commentaire;
use App\Form\CommentType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(BlogRepository $blogRepository): Response
    {
        $blogs = $blogRepository->findAll(); // Récupérer tous les blogs depuis la base de données
        return $this->render('frontoffice/blog/blog.html.twig', [
            'blogs' => $blogs, // Passer les blogs au modèle Twig
        ]);
    }

    #[Route('/blog/{id}', name: 'single_blog')]
    public function show(Blog $blog, ManagerRegistry $doctrine,$id): Response
    {

        $blog=$doctrine->getRepository(Blog::class)->find($id);

        // $comment = new Commentaire();
    // $comment->setBlog($blog); // Set the blog post associated with the comment


    return $this->render('frontoffice/blog/single_blog.html.twig', [
        'blog' => $blog,
        
    ]);
}

    #[Route('/createblog', name: 'create_blog')] 
    public function addblog(Request $request, EntityManagerInterface $entityManager,  ManagerRegistry $doctrine): Response
     {

    $blog = new Blog();
    $blog->setDatePublication(new DateTime()) ;
    $form = $this->createForm(BlogType::class, $blog);
    $form ->add("ADD",SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Get validated data from the form
        $entityManager = $doctrine->getManager();
        $entityManager->persist($blog);
        $entityManager->flush();

        $this->addFlash('success', 'blog created successfully!');
        return   $this->redirectToRoute('blog_list'); // Redirect to user list page
    }

    return $this->render('frontoffice/blog/createblog.html.twig', [
        'form' => $form->createView(),
        'controller_name' => 'BlogController',
    ]);
    }

    #[Route('/listblog', name: 'blog_list')]
    public function listBlogs(): Response
    {
        $blogs = $this->getDoctrine()->getRepository(Blog::class)->findAll();

        return $this->render('frontoffice/blog/list_blog.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_blog')]
    public function edit(BlogRepository $repository, $id, Request $request)
    {
        $blog = $repository->find($id);
        $form = $this->createForm(BlogType::class, $blog);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); //la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute('blog_list');
        }

        return $this->render('frontoffice/blog/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/delete/{id}', name: 'delete_blog')]
    public function delete($id, BlogRepository $repository)
    {
        $blog = $repository->find($id);
        if (!$blog) {
            throw $this->createNotFoundException('blog non trouvé');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();  
        return $this->redirectToRoute('app_blog');
    }
///////////////////////////////////////////commentaire////////////////////////////////////////////////////////////////////////
//     #[Route('/delete-commentfront/{id}', name: 'delete_commentfront')]
//     public function deleteCommentairefront(int $id, Request $request,Security $security): Response
//     {
//         $entityManager = $this->getDoctrine()->getManager();
//         $commentaire = $entityManager->getRepository(commentaire::class)->find($id);

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
//         return new RedirectResponse($referer);;
//     }


    
    
// #[Route('/add-commentfront/{id}', name: 'add_commentfront')]
// public function addCommentairefront($id, Request $request, Security $security): Response
// {
//     $blog = $this->getDoctrine()->getRepository(Blog::class)->find($id);

//     if (!$blog) {
//         throw $this->createNotFoundException('Blog non trouvé avec l\'identifiant '.$id);
//     }

//     $commentaire = new Commentaire();

//     $form = $this->createForm(CommentType::class, $commentaire);

//     $form->handleRequest($request);
//     if ($form->isSubmitted() && $form->isValid()) {
//         // Récupérer l'utilisateur connecté (avec l'ID 3)
//         $adminId = 3; // ID de l'admin statiquement défini à 3
//         $user = $this->getDoctrine()->getRepository(Admin::class)->find($adminId);
//         if (!$user) {
//             throw $this->createNotFoundException('Admin non trouvé avec l\'identifiant '.$adminId);
//         }

//         // $commentaire->setIdadmin($user);

//         $commentaire->setIdblog($blog);

//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->persist($commentaire);
//         $entityManager->flush();

//         return $this->redirectToRoute('blogdetails', ['id' => $commentaire->getIdblog()->getId()]);

//     }

//     return $this->render('blogfront/add.html.twig', [
//         'formadd' => $form->createView(),
//     ]);
// }
// #[Route('/edit-commentfront/{id}', name: 'edit_commentfront')]
// public function editCommentairefront($id, Request $request, Security $security): Response
// {
//     $entityManager = $this->getDoctrine()->getManager();

//     $user = $security->getUser();

//     // Si l'utilisateur n'est pas connecté ou si son ID est différent de 3, redirigez-le ou faites une autre action appropriée
//   //  if (!$user || $user->getID() !== 3) {
//         // Redirection, message d'erreur, etc.
//        // throw $this->createAccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.');
//    // }

//     $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);

//     if (!$commentaire) {
//         throw $this->createNotFoundException('Commentaire non trouvé avec l\'identifiant '.$id);
//     }

//     $form = $this->createForm(CommentType::class, $commentaire);

//     // Traiter le formulaire soumis
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {

//         $entityManager->flush();

//         return $this->redirectToRoute('blogdetails', ['id' => $commentaire->getIdblog()->getId()]);
//     }

//     return $this->render('blogfront/add.html.twig', [
//         'formadd' => $form->createView(),
//     ]);
// }


 }

 
