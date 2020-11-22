<?php

declare(strict_types=1);

namespace App\Exception\Database;

class DatabaseException extends \DomainException
{
    public static function savingEntity(string $message): self
    {
        throw new self(\sprintf('Error saving entity. Message: %s', $message));
    }
}
