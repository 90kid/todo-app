<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function addOrderByCreatedAtQueryBuilder(
        QueryBuilder $queryBuilder = null,
        string $direction = 'ASC'
    ): QueryBuilder {
        $queryBuilder = $queryBuilder ?? $this->createQueryBuilder('task');

        return $queryBuilder->orderBy('task.createdAt', $direction);
    }

    public function userTasksQueryBuilder(User $user, string $orderDirection = 'ASC')
    {
        $queryBuilder = $this
            ->createQueryBuilder('task')
            ->where('task.user = :user_id')
            ->setParameter('user_id', $user->getId());

        return $this->addOrderByCreatedAtQueryBuilder($queryBuilder, $orderDirection);
    }

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
