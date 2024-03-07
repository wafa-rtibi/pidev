<?php

namespace App\Repository;

use App\Entity\Reclamation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * 
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * 
 *  

 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



 public function findByDate($date, $reclamateur_id): array
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.date_reclamation = :date')
        ->andWhere('r.reclamateur = :user')
        ->setParameter('date', $date)
        ->setParameter('user', $reclamateur_id)
        ->getQuery()
        ->getResult();
}



public function findByCriteria($searchTerm, $typeReclamation, $sort)
{
    $queryBuilder = $this->createQueryBuilder('r');

    if ($searchTerm) {
        $queryBuilder
            ->leftJoin('r.reclamateur', 'u')
            ->andWhere('u.nom LIKE :searchTerm OR u.prenom LIKE :searchTerm OR u.email LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }

    if ($typeReclamation) {
        $queryBuilder
            ->andWhere('r.type = :typeReclamation')
            ->setParameter('typeReclamation', $typeReclamation);
    }

    if ($sort == 'asc') {
        $queryBuilder->orderBy('r.date_reclamation', 'ASC');
    } elseif ($sort == 'desc') {
        $queryBuilder->orderBy('r.date_reclamation', 'DESC');
    }

    return $queryBuilder->getQuery()->getResult();
}





public function findByDateAndStatut(int $reclamateur_id, ?string $date, ?string $statut): array
{
    $queryBuilder = $this->createQueryBuilder('r')
        ->andWhere('r.reclamateur = :reclamateur_id')
        ->setParameter('reclamateur_id', $reclamateur_id)
        ->orderBy('r.date_reclamation', 'DESC'); // Tri par date, par défaut en ordre croissant

    if ($date !== null) {
        // Convert the provided date into a DateTime object
        $dateObject = \DateTime::createFromFormat('Y-m-d', $date);

        if ($dateObject instanceof \DateTime) {
            // Set the start of the day
            $dateObject->setTime(0, 0, 0);

            // Set the end of the day
            $endDate = clone $dateObject;
            $endDate->setTime(23, 59, 59);

            // Add the condition in the query for date range
            $queryBuilder
                ->andWhere('r.date_reclamation >= :startDate')
                ->andWhere('r.date_reclamation <= :endDate')
                ->setParameter('startDate', $dateObject)
                ->setParameter('endDate', $endDate);
        }
    }

    if ($statut) {
        $queryBuilder
            ->andWhere('r.statut_reclamation = :statut')
            ->setParameter('statut', $statut);
    }

    return $queryBuilder->getQuery()->getResult();
}




public function getReclamationStatisticsByType(): array
{
    // Logique pour obtenir les statistiques par type de réclamation
    $queryBuilder = $this->createQueryBuilder('r')
        ->select('r.type', 'COUNT(r.id) as count')
        ->groupBy('r.type');

    $results = $queryBuilder->getQuery()->getResult();

    $statistics = [];
    foreach ($results as $result) {
        $statistics[$result['type']] = $result['count'];
    }

    return $statistics;
}



 public function findByUser($id): array
 {
     return $this->createQueryBuilder('o')
         ->join('o.reclamateur', 'u')
         ->addSelect('u')
         ->where('u.id = :val')
         ->setParameter('val', $id)
         ->orderBy('o.date_publication', 'ASC')
         ->getQuery()
         ->getResult();
 }

 

// ReclamationRepository.php
public function findAllNotReserved(): array
{
    return $this->createQueryBuilder('o')
        ->andWhere('o.statut_reclamation = :etat')
        ->setParameter('etat', 'Sent successfully') // Utiliser 'etat' au lieu de 'statut_reclamation'
        ->getQuery()
        ->getResult();
}



 public function findByType($type): array
 {
     return $this->createQueryBuilder('o')
         ->andWhere('o.type = :val')
         ->setParameter('val', $type)
         ->getQuery()
         ->getResult();
 }

 public function paginationQuery()
 {
     return $this->createQueryBuilder('o')
         ->orderBy('o.reclamation','DESC')
         ->getQuery(); //retourne une query de doctrine
     
 }


 public function search($text): array
 {
     return $this->createQueryBuilder('o')
         ->join('o.reclamation', 'u')
         ->addSelect('u')
         //nom
         ->Where('o.nom LIKE :a')
         ->orWhere('o.nom LIKE :b')
         ->orWhere('o.nom LIKE :c')
         ->orWhere('o.nom LIKE :d')
         //description
         ->orWhere('o.description LIKE :a')
         ->orWhere('o.description LIKE :b')
         ->orWhere('o.description LIKE :c')
         ->orWhere('o.description LIKE :d')
         //username
         ->orWhere('u.nom LIKE :a')
         ->orWhere('u.nom LIKE :b')
         ->orWhere('u.nom LIKE :c')
         ->orWhere('u.nom LIKE :d')
         //prenom
         ->orWhere('u.prenom LIKE :a')
         ->orWhere('u.prenom LIKE :b')
         ->orWhere('u.prenom LIKE :c')
         ->orWhere('u.prenom LIKE :d')
         //email
         ->orWhere('u.email LIKE :a')
         ->orWhere('u.email LIKE :b')
         ->orWhere('u.email LIKE :c')
         ->orWhere('u.email LIKE :d')
         //addresse
         ->orWhere('u.adresse LIKE :a')
         ->orWhere('u.adresse LIKE :b')
         ->orWhere('u.adresse LIKE :c')
         ->orWhere('u.adresse LIKE :d')

         ->setParameter('a', $text)
         ->setParameter('b', $text . '%') //start with
         ->setParameter('c', '%' . $text) //end with 
         ->setParameter('d', '%' . $text . '%') // contient
         ->getQuery()
         ->getResult();
 }







}
