<?php

declare(strict_types=1);

namespace App\Domain\Message;

final class RegisteredUserMessage
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var array
     */
    private array $roles;

    /**
     * @var string|null
     */
    private ?string $apiKey;

    /**
     * @param string $id
     * @param string $email
     * @param array $roles
     * @param string|null $apiKey
     */
    public function __construct(string $id, string $email, array $roles, ?string $apiKey = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }
}