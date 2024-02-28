<?php

namespace App\Repository;

use App\Entity\AppelAuDon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppelAuDon>
 *
 * @method AppelAuDon|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppelAuDon|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppelAuDon[]    findAll()
 * @method AppelAuDon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppelAuDonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppelAuDon::class);
    }

//    /**
//     * @return AppelAuDon[] Returns an array of AppelAuDon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AppelAuDon
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
