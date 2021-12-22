<?php

declare(strict_types=1);

namespace App\Application\Mapper\Request;

use App\Application\Mapper\Request\Interfaces\TaskRequestMapperInterface;
use App\Domain\Dto\TaskRequestDto;
use Symfony\Component\HttpFoundation\Request;

final class TaskRequestMapper extends AbstractMapper implements TaskRequestMapperInterface
{
    /**
     * @param Request $request
     * @return TaskRequestDto|null
     */
    public function toTaskDto(Request $request): ?TaskRequestDto
    {
        $name = $request->get('name');
        null !== $name && $name = (string) $name;

        $description = $request->get('description');
        null !== $description && $description = (string) $description;

        $taskRequestDto = new TaskRequestDto(
            name: $name,
            description: $description
        );

        $this->errors = $this->validator->validate($taskRequestDto);

        return $this->hasError() ? null : $taskRequestDto;
    }
}