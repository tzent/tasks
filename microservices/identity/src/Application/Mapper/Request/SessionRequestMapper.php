<?php

declare(strict_types=1);

namespace App\Application\Mapper\Request;

use App\Application\Mapper\Request\Interfaces\SessionRequestMapperInterface;
use App\Domain\Dto\SignInDto;
use App\Domain\Dto\SignOutDto;
use Symfony\Component\HttpFoundation\Request;

final class SessionRequestMapper extends AbstractMapper implements SessionRequestMapperInterface
{
    /**
     * @param Request $request
     * @return SignInDto|null
     */
    public function toSignInDto(Request $request): ?SignInDto
    {
        $email = $request->get('email');
        null !== $email && $email = (string) $email;

        $password = $request->get('password');
        null !== $password && $password = (string) $password;

        $signInDto = new SignInDto(email: $email, password: $password);
        $this->validate($signInDto);

        return !$this->hasError() ? $signInDto : null;
    }

    /**
     * @param Request $request
     * @return SignOutDto
     */
    public function toSignOutDto(Request $request): SignOutDto
    {
        return new SignOutDto($request);
    }
}