<?php

declare(strict_types=1);

namespace App\Domain\Handler;

use App\Domain\Dto\JwtDto;
use App\Domain\Dto\SignInDto;
use App\Domain\Dto\SignOutDto;
use App\Domain\Entity\User;
use App\Domain\Handler\Interfaces\JwtHandlerInterface;
use App\Domain\Handler\Interfaces\UserHandlerInterface;
use App\Infrastructure\Repository\Interfaces\UserRepositoryInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Validator\ConstraintViolation;

final class UserHandler extends AbstractHandler implements UserHandlerInterface
{
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $cache;

    /**
     * @var JwtHandlerInterface
     */
    private JwtHandlerInterface $jwtHandler;

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param AdapterInterface $cache
     * @param JwtHandlerInterface $jwtHandler
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        AdapterInterface $cache,
        JwtHandlerInterface $jwtHandler,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct();

        $this->cache = $cache;
        $this->jwtHandler = $jwtHandler;
        $this->userRepository = $userRepository;
    }

    /**
     * @param SignInDto $signInDto
     * @return JwtDto|null
     */
    public function signIn(SignInDto $signInDto): ?JwtDto
    {
        $email = $signInDto->getEmail();
        /** @var User $user */
        $user = $this->userRepository->findOneByEmail($email);

        if ($user &&
            password_verify($signInDto->getPassword(), $user->getPassword()) &&
            !empty($user->getJwtData())
        ) {
            return new JwtDto($this->jwtHandler->generate($user));
        }

        $this->errors->add(new ConstraintViolation(
            message: 'Wrong user or password',
            messageTemplate: null,
            parameters: [],
            root: 'token',
            propertyPath: 'email',
            invalidValue: $email
        ));

        return null;
    }

    /**
     * @param SignOutDto $signOutDto
     * @return void
     * @throws InvalidArgumentException
     */
    public function signOut(SignOutDto $signOutDto): void
    {
        $cacheKey = $signOutDto->getTokenCacheKey();
        if (!empty($signOutDto->getToken()) && $this->cache->hasItem($cacheKey)) {
            $this->cache->deleteItem($cacheKey);
        }
    }
}