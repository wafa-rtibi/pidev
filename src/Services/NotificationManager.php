<?php
namespace App\Service;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function sendNotification($recipient, $message)
    {
        $date=new \DateTimeImmutable();
        $notification = new Notification();
        $notification->setRecipient($recipient);
        $notification->setMessage($message);
        $notification->setCreatedAt($date);
        $recipient-> addNotification($notification);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
