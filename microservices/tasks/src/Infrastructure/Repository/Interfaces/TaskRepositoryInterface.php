<?php

namespace App\Infrastructure\Repository\Interfaces;

use App\Domain\Entity\Task;

interface TaskRepositoryInterface
{
    /**
     * @param Task $task
     * @return void
     */
    public function save(Task $task): void;
}