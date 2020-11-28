<?php

declare(strict_types=1);

namespace App\Service\Encoder;

use App\Exception\User\InvalidPasswordException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EncoderService
{
    private const MINIMUM_LENGTH = 6;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function generateEncodedPassword(UserInterface $user, string $password): string
    {
        if (self::MINIMUM_LENGTH > \strlen($password)) {
            throw InvalidPasswordException::invalidLength();
        }

        return $this->userPasswordEncoder->encodePassword($user, $password);
    }
}
