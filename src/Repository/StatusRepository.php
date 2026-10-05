<?php

namespace App\Repository;

use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);
    }

    public function countTasks(Status $status): int
    {
        return (int) $this->getEntityManager()->getConnection()->fetchOne(
            'SELECT COUNT(*) FROM tasks WHERE status_id = :status_id',
            ['status_id' => $status->getId()]
        );
    }
}
