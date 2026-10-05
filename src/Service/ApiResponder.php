<?php

namespace App\Service;

use App\Entity\Status;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiResponder
{
    public function task(Task $task, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()->getName(),
            'created_at' => $task->getCreatedAt()->format(DATE_ATOM),
            'updated_at' => $task->getUpdatedAt()->format(DATE_ATOM),
        ], $statusCode);
    }

    /** @param list<Task> $tasks */
    public function tasks(array $tasks): JsonResponse
    {
        return new JsonResponse(array_map(fn (Task $task) => [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()->getName(),
            'created_at' => $task->getCreatedAt()->format(DATE_ATOM),
            'updated_at' => $task->getUpdatedAt()->format(DATE_ATOM),
        ], $tasks));
    }

    public function status(Status $status, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse([
            'id' => $status->getId(),
            'name' => $status->getName(),
            'title' => $status->getTitle(),
        ], $statusCode);
    }

    /** @param list<Status> $statuses */
    public function statuses(array $statuses): JsonResponse
    {
        return new JsonResponse(array_map(fn (Status $status) => [
            'id' => $status->getId(),
            'name' => $status->getName(),
            'title' => $status->getTitle(),
        ], $statuses));
    }
}
