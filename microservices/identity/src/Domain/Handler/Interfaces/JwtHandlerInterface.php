<?php

declare(strict_types=1);

namespace App\Domain\Handler\Interfaces;

use App\Domain\Entity\User;

interface JwtHandlerInterface
{
    public const JWT_PREFIX = 'Bearer';
    public const JWT_NBF       = '+10 minutes';
    public const JWT_TTL       = '+15 minutes';
    public const SESSION_TTL   = '+30 minutes';

    /**
     * @param User $user
     * @return string
     */
    public function generate(User $user): string;
}