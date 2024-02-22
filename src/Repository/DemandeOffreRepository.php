<?php

namespace App\Repository;

use App\Entity\DemandeOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeOffre>
 *
 * @method DemandeOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeOffre[]    findAll()
 * @method DemandeOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeOffre::class);
    }

//    /**
//     * @return DemandeOffre[] Returns an array of DemandeOffre objects
//     */
   public function findByOffre($id): array
   {
       return $this->createQueryBuilder('d')
            ->join('d.offre', 'o')
            ->addSelect('o')
           ->andWhere('o.id= :val')
           ->setParameter('val',$id)
         
           ->getQuery()
           ->getResult()
       ;
   }

   public function findIfSended($id_offre, $id_demandeur): ?array
{
    return $this->createQueryBuilder('d')
        ->leftJoin('d.offre', 'o')
        ->leftJoin('d.demandeur', 'u')
        ->andWhere('o.id = :id_offre')
        ->andWhere('u.id = :id_demandeur')
        ->setParameter('id_offre', $id_offre)
        ->setParameter('id_demandeur', $id_demandeur)
        ->getQuery()
        ->getResult();
}

}
