<?php

declare(strict_types=1);

namespace App\Http;

use App\Exception\Http\HttpClientException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function post(string $uri, array $options): ResponseInterface
    {
        try {
            return $this->client->post($uri, $options);
        } catch (\Exception $e) {
            throw HttpClientException::fromPostRequest($e->getMessage());
        }
    }
}
