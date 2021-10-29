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
    public function findLogsForUser(User $user, ?int $limit = 20): array
    {
        $qb = $this->createQueryBuilder('log');

        $qb->join('log.trackedFood', 'tracked_food');
        $qb->andWhere('tracked_food.user = :user');
        $qb->setParameter('user', $user->getId()->toBinary());
        $qb->orderBy('log.eatenAt', 'DESC');
        $qb->setMaxResults(20);

        return $qb->getQuery()->getResult();
    }

    public function findLastLogFor(TrackedFood $trackedFood): ?Log
    {
        $qb = $this->createQueryBuilder('log');

        $qb->andWhere('log.trackedFood = :trackedFood');
        $qb->setParameter('trackedFood', $trackedFood);
        $qb->orderBy('log.eatenAt', 'DESC');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
