<?php

declare(strict_types=1);

namespace App\Tests\Functional\Service\Http;

use App\Service\Http\ValidateCaptchaTokenInterface;

class ValidateCaptchaTokenService implements ValidateCaptchaTokenInterface
{
    public function __invoke(string $token): void
    {
    }
}
