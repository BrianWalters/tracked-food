<?php

namespace App\Repository;

use App\Entity\TrackedFood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrackedFood|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackedFood|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackedFood[]    findAll()
 * @method TrackedFood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackedFoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackedFood::class);
    }

    // /**
    //  * @return TrackedFood[] Returns an array of TrackedFood objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrackedFood
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
