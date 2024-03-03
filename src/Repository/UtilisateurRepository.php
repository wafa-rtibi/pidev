<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
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

//    public function findOneBySomeField($value): ?Utilisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




public function findOneById($id): ?Utilisateur
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}

public function findOneByEmail($email): ?Utilisateur
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.email = :val')
        ->setParameter('val', $email)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}
public function search():array{
    return $this->findAll();
}

// public function findBySearch(string $search): array
// {
//     $qb = $this->createQueryBuilder('u');

//     $qb->where('u.nom LIKE :search')
//         ->orWhere('u.email LIKE :search')
//         ->orWhere('u.adresse LIKE :search')
//         ->orWhere('u.roles LIKE :search')
//         ->setParameter('search', "%$search%");

//     return $qb->getQuery()->getResult();
// }


// public function findBySearch($search): array
// {
//     $qb = $this->createQueryBuilder('u');

//     // Existing search conditions:
//     $qb->where('u.nom LIKE :search')
//         ->orWhere('u.email LIKE :search')
//         ->orWhere('u.adresse LIKE :search')
//         ->orWhere('u.roles LIKE :search')
//         ->setParameter('search', "%$search%");


//     return $qb->getQuery()->getResult();
// }

// public function filterBySearchAndStatus(?string $search, ?bool $status): array
// {
//     $qb = $this->createQueryBuilder('u')
//         ->select('u');

//     if ($search) {
//         $qb->where('u.username LIKE :search OR u.email LIKE :search')
//             ->setParameter('search', '%'.$search.'%');
//     }

//     if ($status !== null) {
//         $qb->andWhere('u.isActive = :status')
//             ->setParameter('status', $status);
//     }

//     return $qb->getQuery()->getResult();
// }

public function filterBySearchOrStatus(?string $search, ?bool $status): array
{
    $qb = $this->createQueryBuilder('u')
        ->select('u');

    if ($search || $status !== null) {
        $orX = $qb->expr()->orX();

        if ($search) {
            $orX->add(
                $qb->expr()->orX(
                    $qb->expr()->like('u.nom', ':search'),
                    $qb->expr()->like('u.email', ':search'),
                    $qb->expr()->like('u.adresse', ':search'),
                    $qb->expr()->like('u.roles', ':search')
                )
            );
        }

        if ($status !== null) {
            $orX->add($qb->expr()->eq('u.isActive', ':status'));
        }

        $qb->andWhere($orX);

        if ($search) {
            $qb->setParameter('search', "%$search%");
        }

        if ($status !== null) {
            $qb->setParameter('status', $status);
        }
    }

    return $qb->getQuery()->getResult();
}



}
