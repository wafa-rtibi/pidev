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
use App\Repository\UtilisateurRepository;
use App\Entity\Blog; ///////////////////// Import Blog entity
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;


class BlogController extends AbstractController
{
    //home
    #[Route('/blog', name: 'app_blog')]
    public function index(BlogRepository $blogRepository): Response
    {
        $blogs = $blogRepository->findAll(); // Récupérer tous les blogs depuis la base de données
        return $this->render('frontoffice/blog/blog.html.twig', [
            'blogs' => $blogs, // Passer les blogs au modèle Twig
        ]);
    }
    //show blog to user
    #[Route('/blog/{auteur_id}/{id}', name: 'single_blog')]
    public function show(Blog $blog, ManagerRegistry $doctrine,$id): Response
    {

        $blog=$doctrine->getRepository(Blog::class)->find($id);

        // $comment = new Commentaire();
    // $comment->setBlog($blog); // Set the blog post associated with the comment
    $comments = $blog->getComantaires();

    return $this->render('frontoffice/blog/single_blog.html.twig', [
        'blog' => $blog,
        'comments' => $comments,
        
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
public function addblog(Request $request, EntityManagerInterface $entityManager,  ManagerRegistry $doctrine, MailerInterface $mailer): Response
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
    // $email = (new Email())
    // ->from('chamekheya1@gmail.com')
    // ->to('chamekheya1@gmail.com')
    // ->subject('pret!')
    // ->text('You have added a new blog!');

    // $mailer->send($email);
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
    //show blog to admin at dashboard admin
    #[Route('/blog/dashboard', name: 'app_dashboard__blog')]
    public function showdashboard(Request $request, BlogRepository $BlogRepository,$id): Response

    {
    // Récupérer le terme de recherche à partir de la requête GET
    $searchTerm = $request->query->get('q');

    // Récupérer le type de réclamation à partir de la requête GET
    $BlogType = $request->query->get('type');

    // Récupérer l'ordre de tri à partir de la requête GET, avec une valeur par défaut 'asc'
    $sort = $request->query->get('sort', 'asc');

    // Appeler la méthode findByCriteria du BlogRepository pour récupérer les blogs en fonction des critères
    $blogs = $BlogRepository->findByCriteria($searchTerm, $BlogType, $sort);

    // Rendre le template Twig 'frontoffice/reclamation/dashboard_reclamation.html.twig' en passant les réclamations et l'utilisateur connecté
    return $this->render('backoffice/dashboard_blog.html.twig', [
        'tableauBlogs' => $blogs,
        'user' => $this->getUser()
    ]);
    }

    //afficher un blog pour admin
    #[Route('/admin/blog/{auteur_id}/{id}', name: 'app_blog_show_admin')]
    public function show_blog_admin($id,int $auteur_id,UtilisateurRepository $utilisateurRepository,BlogRepository $BlogRepository): Response
    {   

        $user = $utilisateurRepository->find($auteur_id);
        $blog = $BlogRepository->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('backoffice/show_blog_admin.html.twig', [
            'blog' => $user,
            'blogs'=>$blog
        ]);
    }
 

//admin




#[Route('/dashboard/blogs', name: 'app_blogs')]
public function dashboard_bolgs(BlogRepository $blogRepository): Response
{
    $blogs = $blogRepository->findAll(); // Récupérer tous les blogs depuis la base de données

    return $this->render('backoffice/list_all_blogs.html.twig', [
        'blogs' => $blogs, // Passer les blogs au modèle Twig
    ]);
}

//par defaut ki user yajouti blog yahbet direct
//admin fel dashboard list mtaa les blogs el koll ynajm yrejecti

#[Route('/admin/blog/{id}/reject', name: 'admin_blog_reject')]
public function rejectBlog(Blog $blog): Response
{
    // Supprimer le blog de la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($blog);
    $entityManager->flush();

    // Rediriger vers une page de confirmation ou une autre vue
    $this->addFlash('success', 'Le blog a été refusé avec succès.');

    return $this->redirectToRoute('admin_dashboard'); // Remplacer 'admin_dashboard' par la route de votre tableau de bord administrateur
}


}

 
