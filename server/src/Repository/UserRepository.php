<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistsException;

class UserRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return User::class;
    }

    public function save(User $user): void
    {
        try {
            $this->saveEntity($user);
        } catch (\Exception $e) {
            throw UserAlreadyExistsException::fromEmail($user->getEmail());
        }
    }
}
