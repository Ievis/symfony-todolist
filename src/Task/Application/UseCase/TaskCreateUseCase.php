<?php

declare(strict_types=1);

namespace Task\Application\UseCase;

use Task\Application\DTO\CreateTaskRequest;
use Task\Domain\Entity\Task;

class TaskCreateUseCase
{
    public function execute(CreateTaskRequest $request): Task
    {
        $task = new Task();
        $task->setName($data['name']);

        if (!empty($data['description'])) {
            $task->setDescription($data['description']);
        }

        // Автоматически устанавливается через PrePersist
        $task->setCreatedAt();

        $entityManager->persist($task);
        $entityManager->flush();
    }
}