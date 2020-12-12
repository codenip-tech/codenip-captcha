<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Exception\User\InvalidPasswordException;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterActionTest extends WebTestCase
{
    use RecreateDatabaseTrait;

    private const ENDPOINT = '/register';

    private static ?KernelBrowser $client = null;

    public function setUp()
    {
        parent::setUp();

        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameters(
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT' => 'application/json',
                ]
            );
        }
    }

    public function testRegisterUser(): void
    {
        $payload = [
            'name' => 'Juan',
            'email' => 'juan@api.com',
            'password' => 'password123',
            'token' => 'some-token',
        ];

        self::$client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['name'], $responseData['name']);
        self::assertEquals($payload['email'], $responseData['email']);
    }

    public function testRegisterUserWithInvalidPassword(): void
    {
        $payload = [
            'name' => 'Juan',
            'email' => 'juan@api.com',
            'password' => 'pass',
            'token' => 'some-token',
        ];

        self::$client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertEquals(InvalidPasswordException::class, $responseData['class']);
    }

    private function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }
}
