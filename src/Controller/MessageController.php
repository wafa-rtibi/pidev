<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Utilisateur;
use App\Repository\MessagesRepository;
use App\Repository\UtilisateurRepository;
use App\Service\NotificationManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{

     //supprimer conversation *********

    // affichage globale + afficher les utilisateurs que j'ai des conversation avec eux
    #[Route('/message', name: 'app_message')]
    public function show_message(UtilisateurRepository $repo,MessagesRepository $rep): Response
    {
        $users=$this->users($repo,$rep);

        return $this->render('frontoffice/chat/chat.html.twig', [
           'utilisateurs'=> $users
        ]);
    }


    public function users(UtilisateurRepository $repo,MessagesRepository $rep)
    {
        $me = $this->getUser();
        if ($me) {
            $email = $me->getUserIdentifier();
        }
        $me= $repo->findOneByEmail($email);

        $mes_messages=$rep->findMyMessages($me->getId());
        $utilisateurs=[];

       foreach ($mes_messages as $msg) {
            if($msg->getSender()==$me){
                if(!in_array($msg->getRecepient(),$utilisateurs)){
                array_push($utilisateurs, $msg->getRecepient());}
            }
            if($msg->getRecepient()==$me){
                if(!in_array($msg->getSender(),$utilisateurs)){
                array_push($utilisateurs, $msg->getSender());}
            }
       }

        return $utilisateurs;
    }

     // envoyer message
     #[Route('/send/message/{id_user}', name: 'app_message_send')]

     public function send($id_user,Request $req,ManagerRegistry $doctrine,UtilisateurRepository $repo,NotificationManager $notificationManager): Response
     {
        $message=new Messages();

        $user = $this->getUser();
        if ($user) {
            $email = $user->getUserIdentifier();
        }
        
        $sender = $repo->findOneByEmail($email);
        $message->setSender($sender);

        $recepient=$doctrine->getRepository(Utilisateur::class)->find($id_user);
        $message->setRecepient($recepient);

        $contenu=$req->query->get('message');
        $message->setMessage($contenu);
        //ajout msg lel base
        $em= $doctrine->getManager();
        $em->persist($message);
        $em->flush();

        $message = "Vous avez reçu une nouvelle message de " . $sender->getNom();

        // Appel du service de notification pour envoyer la notification
        $notificationManager->sendNotification($recepient, $message);
        
        return $this->redirectToRoute('app_message_show',['id_user'=>$id_user]);
     }

     //afficher conversation/messages avec user particulier
     #[Route('/show/message/{id_user}', name: 'app_message_show')]
     public function showMessageByUser($id_user,UtilisateurRepository $repo,MessagesRepository $rep,ManagerRegistry $doctrine): Response
     {  
                
                
        $me = $this->getUser();
        if ($me) {
            $email = $me->getUserIdentifier();
        }
        $me= $repo->findOneByEmail($email);

        //nlawej aala mesageti m3ah w trie par date 
        $user_messages=$rep->findMessageSendedByUserToMe($id_user,$me->getId());
        $me_messages=$rep->findMessageSendedByMeToUser($id_user,$me->getId());
        $conversation=array_merge($me_messages,$user_messages);

      

        usort($conversation, [self::class, 'compareConversations']);

        $utilisateur=$doctrine->getRepository(Utilisateur::class)->find($id_user);
        $users=$this->users($repo,$rep);
        return $this->render('frontoffice/chat/chat.html.twig', [
             'conversation'=> $conversation,
             'utilisateur'=>$utilisateur,
             'me'=>$me,
             'utilisateurs'=>$users,
        ]);

     }
   
  
     //lancer conversation =>add conversation
     #[Route('/add/message/{id_user}', name: 'app_message_add')]
     public function addConvesation($id_user,ManagerRegistry $doctrine,UtilisateurRepository $repo,MessagesRepository $rep): Response
     {
         $users=$this->users($repo,$rep);
         $user= $doctrine->getRepository(Utilisateur::class)->find($id_user);
         return $this->render('frontoffice/chat/chat.html.twig', [
            'utilisateur'=>$user,
            'utilisateurs'=> $users
         ]);
     }
        //comparison 
       public static function compareConversations($a, $b) {
            $createdAtA = $a->getCreatedAt();
            $createdAtB = $b->getCreatedAt();
        
            if ($createdAtA == $createdAtB) {
                return 0;
            }
            return ($createdAtA < $createdAtB) ? -1 : 1;
        }

}
