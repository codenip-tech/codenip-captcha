<?php

declare(strict_types=1);

namespace App\Exception\Http;

class InvalidCaptchaException extends \DomainException
{
    public static function fromToken(string $token): self
    {
        throw new self(\sprintf('Invalid captcha token %s', $token));
    }
}
