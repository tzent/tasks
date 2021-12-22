<?php

declare(strict_types=1);

namespace App\Domain\Handler\Interfaces;

use App\Domain\Dto\TaskRequestDto;
use App\Domain\Entity\Task;

interface TaskHandlerInterface
{
    /**
     * @param TaskRequestDto $taskRequestDto
     * @return Task
     */
    public function create(TaskRequestDto $taskRequestDto): Task;

    /**
     * @param string $id
     * @return Task|null
     */
    public function edit(string $id): ?Task;
}