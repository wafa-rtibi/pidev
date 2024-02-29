<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNotification(string $recipientEmail, string $reclamationId)
    {
        $email = (new Email())
            ->from('emnakhammassi8@gmail.com')
            ->to($recipientEmail)
            ->subject('Nouvelle réponse à votre réclamation')
            ->html('<p>Une nouvelle réponse a été ajoutée à votre réclamation numéro ' . $reclamationId . '.</p>');

        $this->mailer->send($email);
    }
}