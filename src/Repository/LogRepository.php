<?php

namespace App\Repository;

use App\Entity\Log;
use App\Entity\TrackedFood;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @return Log[]
     */
    public function findLogsForUser(User $user): array
    {
        $qb = $this->createQueryBuilder('log');

        $qb->join('log.trackedFood', 'tracked_food');
        $qb->andWhere('tracked_food.user = :user');
        $qb->setParameter('user', $user->getId()->toBinary());
        $qb->orderBy('log.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findLastLogFor(TrackedFood $trackedFood): ?Log
    {
        $qb = $this->createQueryBuilder('log');

        $qb->andWhere('log.trackedFood = :trackedFood');
        $qb->setParameter('trackedFood', $trackedFood);
        $qb->orderBy('log.createdAt', 'DESC');

        return $qb->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return Log[] Returns an array of Log objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Log
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
