<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class TaskRequestDto
{
    /**
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    private ?string $name;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Type(type: 'string')]
    private ?string $description;

    /**
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(?string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}