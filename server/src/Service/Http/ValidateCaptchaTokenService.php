<?php

declare(strict_types=1);

namespace App\Service\Http;

use App\Exception\Http\InvalidCaptchaException;
use App\Http\HttpClient;

class ValidateCaptchaTokenService implements ValidateCaptchaTokenInterface
{
    private const VALIDATE_CAPTCHA_ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

    private HttpClient $httpClient;
    private string $googleCaptchaSecret;

    public function __construct(HttpClient $httpClient, string $googleCaptchaSecret)
    {
        $this->httpClient = $httpClient;
        $this->googleCaptchaSecret = $googleCaptchaSecret;
    }

    public function __invoke(string $token): void
    {
        $response = $this->httpClient->post(self::VALIDATE_CAPTCHA_ENDPOINT, [
            'form_params' => [
                'secret' => $this->googleCaptchaSecret,
                'response' => $token,
            ],
        ]);

        $data = \json_decode($response->getBody()->getContents(), true);

        if (\array_key_exists('success', $data) && true === $data['success']) {
            return;
        }

        throw InvalidCaptchaException::fromToken($token);
    }
}
