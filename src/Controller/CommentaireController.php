<?php
namespace App\Controller;
use App\Entity\UserController;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Blog; ///////////////////// Import Blog entity
use App\Entity\Commentaire; ///////////////////// Import Blog entity
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\BlogRepository;
use App\Form\BlogType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [    
            'controller_name' => 'CommentaireController',
        ]);
    }
    #[Route('/AddComment/{id}', name: 'app_AddComment')]
    public function AddComment(Blog $blog, Request $request)
    {
        $comment = new Commentaire();
    
        $form = $this->createForm(CommentType::class, $comment);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBlog($blog);
                $em = $this->getDoctrine()->getManager();
    
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('single_blog', [   ////////////////////frontoffice/blog/single_blog.html.twig
                'id' => $blog->getId()]);
        }
    
        return $this->render('blog/showblogdetaille.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog, 
        ]);
    }
  
#[Route('/Affichercomment', name: 'app_afficherlistecomment')]
    public function Show(BlogRepository $repository)
    {
        $blog=$repository->findall();
        return $this->render('comment/Affiche.html.twig',['blog'=>$blog]);
    }

    #[Route('/editcomment/{id}', name: 'app_editcomment')]
    public function edit(BlogRepository $repository, $id, Request $request)
    {
        $blog = $repository->find($id);
        $form = $this->createForm(BlogType::class, $blog);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); 
            return $this->redirectToRoute("app_afficherlistecomment");
        }

        return $this->render('blog/editcomment.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/delete-commentfront/{id}', name: 'delete_commentfront')]
    public function deleteCommentairefront(int $id, Request $request,Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé avec l\'identifiant '.$id);
        }

        $user = $security->getUser();
        $adminId = 1; 
        if ($user && $user->getUserIdentifier() !== $adminId) {
            throw new AccessDeniedException('Vous n\'avez pas le droit de supprimer ce commentaire.');
        }

        $commentaire->getBlog()->removeComantaire($commentaire);

        $entityManager->remove($commentaire);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }


    function filterComment(string $text_comment): string {
        $badWords = file('C:\xampp\htdocs\public\uploads\badwords.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($badWords as $word) {
            $replacement = $word[0] . str_repeat('*', strlen($word) - 2) . $word[-1];
            $text_comment = preg_replace('/\b' . preg_quote($word) . '\b/i', $replacement, $text_comment);
        }
        return $text_comment;
    }

    #[Route('/add-commentfront/{id}', name: 'add_commentfront')]
    public function addCommentairefront($id, Request $request, Security $security): Response
    {
        $blog = $this->getDoctrine()->getRepository(Blog::class)->find($id);
    
        if (!$blog) {
            throw $this->createNotFoundException('Blog non trouvé avec l\'identifiant '.$id);
        }
    
         $commentaire = new Commentaire();
    
        $form = $this->createForm(CommentType::class, $commentaire);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
          // Récupérer l'utilisateur connecté (avec l'ID 201)
            $adminId = 1; 
            $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($adminId);

            if (!$user) {
                throw $this->createNotFoundException('Admin non trouvé avec l\'identifiant '.$adminId);
            }
            
    
             //$commentaire->setId ($blog);
            $commentaire->setBlog ($blog);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('single_blog', ['id' => $commentaire->getBlog()->getId()]);
    
        }
        dump($form);
        return $this->render('frontoffice/blog/addcomment.html.twig', [
           'formadd' => $form->createView(),
        ]);
    }

    #[Route('/edit-commentfront/{id}', name: 'edit_commentfront')]
    public function editCommentairefront($id, Request $request, Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $user = $security->getUser();
            
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);
    
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé avec l\'identifiant '.$id);
        }
    
        $form = $this->createForm(CommentType::class, $commentaire); // Replace CommentType::class with CommentaireType::class
    
        // Traiter le formulaire soumis
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            $entityManager->flush();
    
            return $this->redirectToRoute('single_blog', ['id' => $commentaire->getBlog()->getId()]);
        }
    
        return $this->render('frontoffice/blog/addcomment.html.twig', [
            'formadd' => $form->createView(),
        ]);
    }
    

}