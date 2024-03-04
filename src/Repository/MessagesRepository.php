<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Messages>
 *
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

   /**
    * @return Messages[] Returns an array of Messages objects
    */
   public function findMessageSendedByMeToUser($id_user,$id_me): array
   {
       return $this->createQueryBuilder('m')
           ->leftJoin('m.recepient', 'u1')
           ->leftJoin('m.sender', 'u2')
           ->Where('u1.id = :user')
           ->andWhere('u2.id = :me')
           ->setParameter('user', $id_user)
           ->setParameter('me', $id_me)
           ->OrderBy('m.created_at','ASC')
           ->getQuery()
           ->getResult()
       ;
   }

     /**
    * @return Messages[] Returns an array of Messages objects
    */
    public function findMessageSendedByUserToMe($id_user,$id_me): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.recepient', 'u1')
            ->leftJoin('m.sender', 'u2')
            ->Where('u2.id = :user')
            ->andWhere('u1.id = :me')
            ->setParameter('user', $id_user)
            ->setParameter('me', $id_me)
            ->OrderBy('m.created_at','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Messages[] Returns an array of Messages objects
    */
    public function findMyMessages($id_me): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.recepient', 'u1')
            ->leftJoin('m.sender', 'u2')
            ->Where('u2.id = :me')
            ->orWhere('u1.id = :me')
            ->setParameter('me', $id_me)
            ->OrderBy('m.created_at','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Messages
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
