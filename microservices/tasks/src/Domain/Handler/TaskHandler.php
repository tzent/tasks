<?php

declare(strict_types=1);

namespace App\Domain\Handler;

use App\Domain\Dto\TaskRequestDto;
use App\Domain\Entity\Task;
use App\Domain\Handler\Interfaces\TaskHandlerInterface;
use App\Infrastructure\Repository\Interfaces\TaskRepositoryInterface;
use Symfony\Component\Validator\ConstraintViolation;

final class TaskHandler extends AbstractHandler implements TaskHandlerInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $taskRepository;

    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        parent::__construct();

        $this->taskRepository = $taskRepository;
    }

    /**
     * @param TaskRequestDto $taskRequestDto
     * @return Task
     */
    public function create(TaskRequestDto $taskRequestDto): Task
    {
        $task = (new Task())
            ->setName($taskRequestDto->getName())
            ->setDescription($taskRequestDto->getDescription());

        $this->taskRepository->save($task);

        return $task;
    }

    /**
     * @param string $id
     * @return Task|null
     */
    public function edit(string $id): ?Task
    {
        /** @var Task $task */
        $task = $this->taskRepository->findOneById($id);
        if (null !== $task) {
            if ($task->getStatus() === Task::STATUS_OPEN) {
                $task->setStatus(Task::STATUS_CLOSED);
                $this->taskRepository->save($task);
            } else {
                $this->errors->add(new ConstraintViolation(
                    message: 'Task already closed',
                    messageTemplate: null,
                    parameters: [],
                    root: 'task',
                    propertyPath: 'id',
                    invalidValue: $id
                ));
            }

            return $task;
        }

        $this->errors->add(new ConstraintViolation(
            message: 'Task not found',
            messageTemplate: null,
            parameters: [],
            root: 'task',
            propertyPath: 'id',
            invalidValue: $id
        ));

        return null;
    }
}