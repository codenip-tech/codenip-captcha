<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\Http\HttpClientException;
use App\Exception\Http\InvalidCaptchaException;
use App\Exception\User\InvalidEmailException;
use App\Exception\User\InvalidPasswordException;
use App\Exception\User\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class JsonExceptionResponseTransformerListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $data = [
            'class' => \get_class($exception),
            'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage(),
        ];

        if (\in_array($data['class'], $this->getBadRequestExceptions(), true)) {
            $data['code'] = JsonResponse::HTTP_BAD_REQUEST;
        }

        if (\in_array($data['code'], $this->getConflictExceptions(), true)) {
            $data['code'] = JsonResponse::HTTP_CONFLICT;
        }

        $event->setResponse($this->prepareResponse($data));
    }

    private function prepareResponse(array $data): JsonResponse
    {
        $response = new JsonResponse($data, $data['code']);
        $response->headers->set('Server-Time', \time());
        $response->headers->set('X-Error-Code', $data['code']);

        return $response;
    }

    private function getBadRequestExceptions(): array
    {
        return [
            HttpClientException::class,
            InvalidCaptchaException::class,
            InvalidEmailException::class,
            InvalidPasswordException::class,
        ];
    }

    private function getConflictExceptions(): array
    {
        return [UserAlreadyExistsException::class];
    }
}
