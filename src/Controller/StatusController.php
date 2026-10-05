<?php

namespace App\Controller;

use App\Dto\CreateStatusRequest;
use App\Repository\StatusRepository;
use App\Service\ApiResponder;
use App\Service\JsonRequestDecoder;
use App\Service\StatusManager;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/statuses', name: 'api_statuses_')]
final class StatusController extends AbstractController
{
    public function __construct(
        private readonly StatusManager $statusManager,
        private readonly JsonRequestDecoder $decoder,
        private readonly ApiResponder $responder,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Get(path: '/api/statuses', summary: 'List statuses')]
    public function list(): JsonResponse
    {
        return $this->responder->statuses($this->statusManager->list());
    }

    #[Route('/{id<\\d+>}', name: 'get', methods: ['GET'])]
    #[OA\Get(path: '/api/statuses/{id}', summary: 'Get a status')]
    public function get(int $id): JsonResponse
    {
        return $this->responder->status($this->statusManager->get($id));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(path: '/api/statuses', summary: 'Create a status')]
    public function create(Request $request): JsonResponse
    {
        $data = $this->decoder->decode($request);
        $dto = new CreateStatusRequest($data['name'] ?? null, $data['title'] ?? null);
        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            return $this->validationError($violations);
        }
        if (!is_string($dto->name) || !is_string($dto->title)) {
            return new JsonResponse(['error' => 'name and title must be strings.'], 422);
        }

        return $this->responder->status($this->statusManager->create($dto->name, $dto->title), 201);
    }

    #[Route('/{id<\\d+>}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(path: '/api/statuses/{id}', summary: 'Delete a status')]
    public function delete(int $id): JsonResponse
    {
        $this->statusManager->delete($this->statusManager->get($id));
        return new JsonResponse(null, 204);
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
