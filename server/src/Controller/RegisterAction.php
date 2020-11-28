<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\User\RegisterUserActionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterAction
{
    private RegisterUserActionService $registerUserActionService;

    public function __construct(RegisterUserActionService $registerUserActionService)
    {
        $this->registerUserActionService = $registerUserActionService;
    }

    /**
     * @Route("/register", methods={"POST"}, name="register_user")
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);

        $user = $this->registerUserActionService->__invoke($data['name'], $data['email'], $data['password'], $data['token']);

        return new JsonResponse([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'createdOn' => $user->getCreatedOn()->format(\DateTime::RFC3339),
            'updatedOn' => $user->getUpdatedOn()->format(\DateTime::RFC3339),
        ], JsonResponse::HTTP_CREATED);
    }
}
