<?php

namespace App\Controller;

use App\Dto\ChangeTaskStatusRequest;
use App\Dto\CreateTaskRequest;
use App\Repository\StatusRepository;
use App\Service\ApiResponder;
use App\Service\JsonRequestDecoder;
use App\Service\TaskManager;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks', name: 'api_tasks_')]
final class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskManager $taskManager,
        private readonly StatusRepository $statusRepository,
        private readonly JsonRequestDecoder $decoder,
        private readonly ApiResponder $responder,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(path: '/api/tasks', summary: 'Create a task')]
    public function create(Request $request): JsonResponse
    {
        $data = $this->decoder->decode($request);
        $dto = new CreateTaskRequest($data['title'] ?? null, $data['description'] ?? null);
        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            return $this->validationError($violations);
        }

        if (!is_string($dto->title)) {
            return new JsonResponse(['error' => 'title must be a string.'], 422);
        }
        if ($dto->description !== null && !is_string($dto->description)) {
            return new JsonResponse(['error' => 'description must be a string or null.'], 422);
        }

        $status = $this->statusRepository->findOneBy(['name' => 'new']);
        if ($status === null) {
            throw new NotFoundHttpException('Default status "new" not found.');
        }

        $task = $this->taskManager->create($dto->title, $dto->description, $status);
        return $this->responder->task($task, 201);
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Get(path: '/api/tasks', summary: 'List tasks', parameters: [new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string'))])]
    public function list(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        if ($status !== null && (!is_string($status) || !preg_match('/^[a-z0-9]+(?:_[a-z0-9]+)*$/', $status))) {
            return new JsonResponse(['error' => 'Invalid status filter.'], 422);
        }

        if ($status !== null && $this->statusRepository->findOneBy(['name' => $status]) === null) {
            return new JsonResponse(['error' => 'Status not found.'], 404);
        }

        return $this->responder->tasks($this->taskManager->list($status));
    }

    #[Route('/{id<\\d+>}', name: 'get', methods: ['GET'])]
    #[OA\Get(path: '/api/tasks/{id}', summary: 'Get a task')]
    public function get(int $id): JsonResponse
    {
        return $this->responder->task($this->taskManager->get($id));
    }

    #[Route('/{id<\\d+>}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(path: '/api/tasks/{id}', summary: 'Delete a task')]
    public function delete(int $id): JsonResponse
    {
        $this->taskManager->delete($this->taskManager->get($id));
        return new JsonResponse(null, 204);
    }

    #[Route('/{id<\\d+>}/status', name: 'change_status', methods: ['PATCH'])]
    #[OA\Patch(path: '/api/tasks/{id}/status', summary: 'Change task status')]
    public function changeStatus(int $id, Request $request): JsonResponse
    {
        $task = $this->taskManager->get($id);
        $data = $this->decoder->decode($request);
        $dto = new ChangeTaskStatusRequest($data['status'] ?? null);
        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            return $this->validationError($violations);
        }
        if (!is_string($dto->status)) {
            return new JsonResponse(['error' => 'status must be a string.'], 422);
        }

        $status = $this->statusRepository->findOneBy(['name' => $dto->status]);
        if ($status === null) {
            return new JsonResponse(['error' => 'Status not found.'], 404);
        }

        return $this->responder->task($this->taskManager->changeStatus($task, $status));
    }

    private function validationError(\Symfony\Component\Validator\ConstraintViolationListInterface $violations): JsonResponse
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return new JsonResponse(['error' => 'Validation failed.', 'details' => $errors], 422);
    }
}
