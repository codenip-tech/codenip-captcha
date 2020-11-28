<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Encoder\EncoderService;
use App\Service\Http\ValidateCaptchaTokenInterface;

class RegisterUserActionService
{
    private UserRepository $userRepository;
    private ValidateCaptchaTokenInterface $validateCaptchaToken;
    private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, ValidateCaptchaTokenInterface $validateCaptchaToken, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->validateCaptchaToken = $validateCaptchaToken;
        $this->encoderService = $encoderService;
    }

    public function __invoke(string $name, string $email, string $password, string $token): User
    {
        $this->validateCaptchaToken->__invoke($token);

        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));

        $this->userRepository->save($user);

        return $user;
    }
}
