<?php

namespace App\Infrastructure\Repository\Interfaces;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void;
}