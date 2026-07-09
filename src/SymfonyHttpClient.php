<?php

declare(strict_types=1);

namespace Gonon\Http\Symfony;

use Gonon\Core\Contracts\HttpClientInterface;
use Gonon\Core\Contracts\RequestInterface;
use Gonon\Core\Contracts\ResponseInterface;
use Gonon\Core\Exceptions\NetworkException;
use Gonon\Core\Http\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyClientInterface;

final readonly class SymfonyHttpClient implements HttpClientInterface
{
    private SymfonyClientInterface $client;

    public function __construct(?SymfonyClientInterface $client = null)
    {
        $this->client = $client ?? HttpClient::create();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $options = [];

            if ($request->getHeaders() !== []) {
                $options['headers'] = $request->getHeaders();
            }

            if ($request->getBody() !== null) {
                $options['body'] = $request->getBody();
            }

            $response = $this->client->request(
                $request->getMethod(),
                $request->getUri(),
                $options
            );

            return new Response(
                statusCode: $response->getStatusCode(),
                headers: $response->getHeaders(false),
                body: $response->getContent(false),
            );
        } catch (ExceptionInterface $e) {
            throw new NetworkException($e->getMessage(), $request, null, $e);
        }
    }
}
