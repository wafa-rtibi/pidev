<?php

namespace App\Controller;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\BlogRepository;
use App\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Blog; ///////////////////// Import Blog entity
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\EntityManagerInterface;

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
// #[Route('/blog/{id}', name: 'single_blog')]

//         public function show( $id,ManagerRegistry $doctrine):Response{
            
            
//             $BlogRepository = $doctrine->getRepository(Blog::class);
//             $blog=$doctrine->getRepository(Blog::class)->find($id);
//             $user = $blog->getAuteur();
//             $auteur = $user->getNom();

//             // $comments = $blog->getComantaires();
//             return $this->render('frontoffice/blog/single_blog.html.twig', [
//                 'blog' => $blog,
//                 // 'comments' => $comments,
//                 // 'auteur' => $username,
//             ]);
            
//         }

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
        return   $this->render('backoffice/dashAdminBlog.html.twig'); // Redirect to user list page
    }

     //return $this->render('backoffice/dashAdminBlog.html.twig', [
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

 }

 
