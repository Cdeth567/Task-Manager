<?php

namespace App\Service;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class StatusManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StatusRepository $statusRepository,
    ) {
    }

    public function create(string $name, string $title): Status
    {
        $existing = $this->statusRepository->findOneBy(['name' => $name]);
        if ($existing instanceof Status) {
            throw new ConflictHttpException('Status name already exists.');
        }

        $status = new Status($name, $title);
        $this->entityManager->persist($status);
        $this->entityManager->flush();

        return $status;
    }

    public function get(int $id): Status
    {
        $status = $this->statusRepository->find($id);
        if (!$status instanceof Status) {
            throw new NotFoundHttpException('Status not found.');
        }

        return $status;
    }

    /** @return list<Status> */
    public function list(): array
    {
        return $this->statusRepository->findBy([], ['id' => 'ASC']);
    }

    public function delete(Status $status): void
    {
        if ($this->statusRepository->countTasks($status) > 0) {
            throw new ConflictHttpException('Status is used by one or more tasks and cannot be deleted.');
        }

        $this->entityManager->remove($status);
        $this->entityManager->flush();
    }
}
