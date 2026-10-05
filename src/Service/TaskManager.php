<?php

namespace App\Service;

use App\Entity\Status;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TaskManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaskRepository $taskRepository,
    ) {
    }

    public function create(string $title, ?string $description, Status $status): Task
    {
        $task = new Task($title, $description, $status);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function get(int $id): Task
    {
        $task = $this->taskRepository->find($id);
        if (!$task instanceof Task) {
            throw new NotFoundHttpException('Task not found.');
        }

        return $task;
    }

    /** @return list<Task> */
    public function list(?string $status): array
    {
        return $this->taskRepository->findByStatusName($status);
    }

    public function changeStatus(Task $task, Status $status): Task
    {
        $task->setStatus($status);
        $this->entityManager->flush();

        return $task;
    }

    public function delete(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
}
