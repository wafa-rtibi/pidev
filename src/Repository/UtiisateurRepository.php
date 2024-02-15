<?php

namespace App\Repository;

use App\Entity\Utiisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utiisateur>
 *
 * @method Utiisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utiisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utiisateur[]    findAll()
 * @method Utiisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtiisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utiisateur::class);
    }

//    /**
//     * @return Utiisateur[] Returns an array of Utiisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Utiisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
