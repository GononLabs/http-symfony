<?php

declare(strict_types=1);

namespace Gonon\Http\Symfony\Tests;

use Gonon\Core\Exceptions\NetworkException;
use Gonon\Core\Http\Request;
use Gonon\Http\Symfony\SymfonyHttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as SymfonyResponseInterface;

class SymfonyHttpClientTest extends TestCase
{
    public function test_it_sends_request_and_maps_response(): void
    {
        $symfonyClient = $this->createMock(SymfonyClientInterface::class);
        $symfonyResponse = $this->createMock(SymfonyResponseInterface::class);

        $symfonyClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://example.com/api',
                [
                    'headers' => ['Accept' => ['application/json']],
                    'body' => '{"key":"value"}',
                ]
            )
            ->willReturn($symfonyResponse);

        $symfonyResponse->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(201);

        $symfonyResponse->expects($this->once())
            ->method('getHeaders')
            ->with(false)
            ->willReturn(['content-type' => ['application/json']]);

        $symfonyResponse->expects($this->once())
            ->method('getContent')
            ->with(false)
            ->willReturn('{"success":true}');

        $adapter = new SymfonyHttpClient($symfonyClient);
        $request = new Request('POST', 'https://example.com/api', ['Accept' => ['application/json']], '{"key":"value"}');

        $response = $adapter->sendRequest($request);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame(['content-type' => ['application/json']], $response->getHeaders());
        $this->assertSame('{"success":true}', $response->getBody());
    }

    public function test_it_wraps_exceptions(): void
    {
        $symfonyClient = $this->createMock(SymfonyClientInterface::class);

        $exception = new class() extends \Exception implements TransportExceptionInterface
        {
            public function __construct()
            {
                parent::__construct('Connection timeout');
            }
        };

        $symfonyClient->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $adapter = new SymfonyHttpClient($symfonyClient);
        $request = new Request('GET', 'https://example.com');

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Connection timeout');

        $adapter->sendRequest($request);
    }
}
