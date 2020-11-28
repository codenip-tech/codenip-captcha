<?php

declare(strict_types=1);

namespace App\Service\Http;

interface ValidateCaptchaTokenInterface
{
    public function __invoke(string $token): void;
}
