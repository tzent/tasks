<?php

declare(strict_types=1);

namespace App\Domain\Message;

final class ApiConsumerMessage
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var array
     */
    private array $jwtData;

    /**
     * @param string $id
     * @param array $jwtData
     */
    public function __construct(string $id, array $jwtData)
    {
        $this->id = $id;
        $this->jwtData = $jwtData;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getJwtData(): array
    {
        return $this->jwtData;
    }
}