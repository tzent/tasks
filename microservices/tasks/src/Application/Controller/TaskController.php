<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Mapper\Request\Interfaces\TaskRequestMapperInterface;
use App\Domain\Handler\Interfaces\TaskHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/tasks/v1/task')]
final class TaskController extends AbstractController
{
    /**
     * @var TaskRequestMapperInterface
     */
    private TaskRequestMapperInterface $taskRequestMapper;

    /**
     * @var TaskHandlerInterface
     */
    private TaskHandlerInterface $taskHandler;

    /**
     * @param TaskRequestMapperInterface $taskRequestMapper
     * @param TaskHandlerInterface $taskHandler
     */
    public function __construct(
        TaskRequestMapperInterface $taskRequestMapper,
        TaskHandlerInterface $taskHandler
    ) {
        $this->taskRequestMapper = $taskRequestMapper;
        $this->taskHandler = $taskHandler;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route(path: '/create', name: 'task-create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $taskRequestDto = $this->taskRequestMapper->toTaskDto($request);
        if ($this->taskRequestMapper->hasError()) {
            return $this->json($this->taskRequestMapper->getError(), Response::HTTP_BAD_REQUEST);
        }

        $task = $this->taskHandler->create($taskRequestDto);

        return $this->taskHandler->hasError()
            ? $this->json(['error' => $this->taskHandler->getError()], Response::HTTP_BAD_REQUEST)
            : $this->json($task);

    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route(path: '/edit/{id}', name: 'task-edit', methods: ['POST'])]
    public function edit(string $id): JsonResponse
    {
        $task = $this->taskHandler->edit($id);

        if (null === $task) {
            return $this->json(['error' => $this->taskHandler->getError()], Response::HTTP_NOT_FOUND);
        }

        return $this->taskHandler->hasError()
            ? $this->json(['error' => $this->taskHandler->getError()], Response::HTTP_BAD_REQUEST)
            : $this->json($task);
    }
}