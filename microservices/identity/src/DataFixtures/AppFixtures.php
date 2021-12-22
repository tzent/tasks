<?php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use App\Domain\Message\RegisteredUserMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param MessageBusInterface $messageBus
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        MessageBusInterface $messageBus,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->messageBus = $messageBus;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $hashedPass = $this->userPasswordHasher->hashPassword(new User(),'password123');
        $employeeUser = (new User())
            ->setEmail('employee@company.com')
            ->setPassword($hashedPass)
            ->setApiKey('Qslaip2ruiwcusuSUdhXPv4SORZrfj4L');

        $manager->persist($employeeUser);

        $managerUser = (new User())
            ->setEmail('manager@company.com')
            ->setPassword($hashedPass)
            ->setApiKey('nCztu5Jrz18YAWmkwOGJkQe9T8lB99l4')
            ->setRoles(['manager']);

        $manager->persist($managerUser);
        $manager->flush();

        $this->sendUser($employeeUser);
        $this->sendUser($managerUser);
    }

    /**
     * @param User $user
     * @return void
     */
    private function sendUser(User $user): void
    {
        $this->messageBus->dispatch(
            new RegisteredUserMessage(
                id: $user->getId(),
                email: $user->getEmail(),
                roles: $user->getRoles(),
                apiKey: $user->getApiKey()
            )
        );
    }
}
