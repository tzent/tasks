<?php

declare(strict_types=1);

namespace App\Application\Mapper\Request\Interfaces;

use App\Domain\Dto\SignInDto;
use App\Domain\Dto\SignOutDto;
use Symfony\Component\HttpFoundation\Request;

interface SessionRequestMapperInterface
{
    /**
     * @param Request $request
     * @return SignInDto|null
     */
    public function toSignInDto(Request $request): ?SignInDto;

    /**
     * @param Request $request
     * @return SignOutDto
     */
    public function toSignOutDto(Request $request): SignOutDto;
}