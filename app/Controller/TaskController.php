<?php

namespace App\Controller;

use App\Common\Infrastructure\Response\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Task\Application\DTO\CreateTaskRequest;
use Task\Application\UseCase\TaskCreateUseCase;

#[Route('/api/task', name: 'app_tasks_')]
class TaskController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="createdAt", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    #[Route('', name: 'create', methods: ['GET'])]
    public function create(CreateTaskRequest $request, TaskCreateUseCase $useCase, JsonResponse $response): JsonResponse
    {
        return $response->ok($useCase->execute($request), Response::HTTP_CREATED);
    }
}
