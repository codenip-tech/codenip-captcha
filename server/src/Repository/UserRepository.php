<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserAlreadyExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
        } catch (UniqueConstraintViolationException $e) {
            throw UserAlreadyExistsException::fromEmail($user->getEmail());
        }
    }
}
