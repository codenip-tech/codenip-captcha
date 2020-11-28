<?php

declare(strict_types=1);

namespace App\Exception\Http;

class HttpClientException extends \DomainException
{
    public static function fromPostRequest(string $message): self
    {
        throw new self(\sprintf('Error in POST call. Message: %s', $message));
    }
}
