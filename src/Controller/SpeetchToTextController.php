<?php

// namespace App\Controller;

// use OpenAI\Client;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;

// class SpeechToTextController extends AbstractController
// {
//     private $openaiClient;

//     public function __construct(Client $openaiClient)
//     {
//         $this->openaiClient = $openaiClient;
//     }

//     public function convertSpeechToText(Request $request): Response
//     {
//         // Récupérer le fichier audio depuis la requête
//         $audioFile = $request->files->get('audio');

//         // Envoyer le fichier audio à l'API OpenAI pour conversion
//         $response = $this->openaiClient->speechToText($audioFile);

//         // Récupérer le texte converti à partir de la réponse
//         $convertedText = $response['text'];

//         // Retourner une réponse JSON avec le texte converti
//         return new JsonResponse(['text' => $convertedText]);
//     }
// }
