<?php

declare(strict_types=1);

namespace App\Domain\Handler\Message;

use App\Domain\Entity\User;
use App\Domain\Message\ApiConsumerMessage;
use App\Infrastructure\Repository\Interfaces\UserRepositoryInterface;
use ErrorException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ApiConsumerHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ApiConsumerMessage $message
     * @return void
     * @throws ErrorException
     */
    public function __invoke(ApiConsumerMessage $message): void
    {
        $userId = $message->getId();
        /** @var User $user */
        $user = $this->userRepository->findOneById($userId);

        if (null === $user) {
            throw new ErrorException('User with id' . $userId . ' was not found.');
        }

        $user->setJwtData($message->getJwtData());
        $this->userRepository->save($user);
    }
}