<?php

declare(strict_types=1);

namespace App\Domain\Handler\Interfaces;

use App\Domain\Dto\JwtDto;
use App\Domain\Dto\SignInDto;
use App\Domain\Dto\SignOutDto;

interface UserHandlerInterface
{
    /**
     * @param SignInDto $signInDto
     * @return JwtDto|null
     */
    public function signIn(SignInDto $signInDto): ?JwtDto;

    /**
     * @param SignOutDto $signOutDto
     * @return void
     */
    public function signOut(SignOutDto $signOutDto): void;
}