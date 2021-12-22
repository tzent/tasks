<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use Symfony\Component\HttpFoundation\Request;

class AuthenticationDto
{
    /**
     * @var string|null
     */
    private ?string $userId;

    /**
     * @var string|null
     */
    private ?string $userEmail;

    /**
     * @var JwtDto
     */
    private JwtDto $tokenDTO;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->userId    = $request->headers->get('x-consumer-custom-id');
        $this->userEmail = $request->headers->get('x-consumer-username');
        $this->tokenDTO  = new JwtDto($request->headers->get('authorization', ''));
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->tokenDTO->getToken();
    }

    /**
     * @return string
     */
    public function getTokenCacheKey(): string
    {
        return sprintf('%s-%s', $this->userId, $this->getToken());
    }
}
