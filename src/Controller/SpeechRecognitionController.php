<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpeechRecognitionController extends AbstractController
{
    /**
     * @Route("/speech-recognition", name="speech_recognition")
     */
    public function index(): Response
    {
        return $this->render('frontoffice/blog/speech_recognition.html.twig');
    }
}