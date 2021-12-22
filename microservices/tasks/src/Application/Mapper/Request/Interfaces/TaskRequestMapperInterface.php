<?php

declare(strict_types=1);

namespace App\Application\Mapper\Request\Interfaces;

use App\Domain\Dto\TaskRequestDto;
use Symfony\Component\HttpFoundation\Request;

interface TaskRequestMapperInterface
{
    /**
     * @param Request $request
     * @return TaskRequestDto|null
     */
    public function toTaskDto(Request $request): ?TaskRequestDto;
}