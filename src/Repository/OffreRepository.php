<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    //    /**
    //     * @return Offre[] Returns an array of Offre objects
    //     */
    public function findByUser($id): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.offreur', 'u')
            ->addSelect('u')
            ->where('u.id = :val')
            ->setParameter('val', $id)
            ->orderBy('o.date_publication', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //******mes offres non reservé *******//

    public function findByUserNotReserved($id): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.offreur', 'u')
            ->addSelect('u')
            ->where('u.id = :val')
            ->andWhere('o.etat = :etat')
            ->setParameter('etat', 'publié')
            ->setParameter('val', $id)
            ->orderBy('o.date_publication', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findByUserAndOffre($id_offre, $id_user): ?array
    {
        return $this->createQueryBuilder('o')
            ->join('o.offreur', 'u')
            ->addSelect('u')
            ->where('u.id = :val2')
            ->andWhere('o.id = :val1')
            ->setParameter('val1', $id_offre)
            ->setParameter('val2', $id_user)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findAllNotReserved(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.etat = :etat')
            ->setParameter('etat', 'publié')
            ->getQuery()
            ->getResult();
    }


    public function findByCategory($category): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.categorie = :val')
            ->setParameter('val', $category)
            ->getQuery()
            ->getResult();
    }

    public function paginationQuery()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.id','ASC')
            ->getQuery(); //retourne une query de doctrine
        
    }


    public function search($text): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.offreur', 'u')
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

    //stat
    public function getOffreStatisticsByType(): array
{
    // Logique pour obtenir les statistiques par type de réclamation
    $queryBuilder = $this->createQueryBuilder('o')
        ->select('o.etat', 'COUNT(o.id) as count')
        ->groupBy('o.etat');

    $results = $queryBuilder->getQuery()->getResult();

    $statistics = [];
    foreach ($results as $result) {
        $statistics[$result['etat']] = $result['count'];
    }

    return $statistics;
}
}
