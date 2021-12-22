<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use App\Domain\Handler\Interfaces\JwtHandlerInterface;

final class JwtDto
{
    /**
     * @var string
     */
    private string $token;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        if (0 === stripos($token, JwtHandlerInterface::JWT_PREFIX)) {
            $cnt   = 1;
            $token = str_ireplace(JwtHandlerInterface::JWT_PREFIX, '', $token, $cnt);
        }

        $this->token = trim($token);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}