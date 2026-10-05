<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /** @return list<Task> */
    public function findByStatusName(?string $status): array
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.status', 's')
            ->addSelect('s')
            ->orderBy('t.id', 'DESC');

        if ($status !== null && $status !== '') {
            $qb->andWhere('s.name = :status')->setParameter('status', $status);
        }

        return $qb->getQuery()->getResult();
    }
}
