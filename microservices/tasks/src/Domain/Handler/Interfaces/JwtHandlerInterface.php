<?php

declare(strict_types=1);

namespace App\Domain\Handler\Interfaces;

interface JwtHandlerInterface
{
    public const JWT_PREFIX = 'Bearer';
    public const JWT_NBF       = '+10 minutes';
    public const JWT_TTL       = '+15 minutes';
    public const SESSION_TTL   = '+30 minutes';
}