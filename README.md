# Gonon Symfony HTTP Adapter

This package provides the default Symfony HTTP Client adapter for the Gonon ecosystem.
It acts as the bridge between `Gonon\Core\Contracts\HttpClientInterface` and `symfony/http-client`.

## Installation

```bash
composer require gonon/http-symfony
```

## Usage

This package provides a concrete adapter for the core HTTP client interface.

```php
use Gonon\Core\Http\Client;
use Gonon\Core\Configuration\Config;
use Gonon\Http\Symfony\SymfonyHttpClient;

// 1. Create the Symfony Adapter
$adapter = new SymfonyHttpClient();

// 2. Pass it to the Core HTTP Orchestrator
$client = new Client(adapter: $adapter);

// Now you can pass $client to any Gonon SDK (Tripay, Xendit, etc.)
```

## Customizing the Symfony Client

You can inject a fully configured Symfony HTTP Client instance into the adapter if you need specific options (e.g., proxies, certs).

```php
use Symfony\Component\HttpClient\HttpClient;
use Gonon\Http\Symfony\SymfonyHttpClient;

$symfonyClient = HttpClient::create([
    'max_duration' => 5,
    'proxy' => 'http://proxy.example.com:8080'
]);

$adapter = new SymfonyHttpClient($symfonyClient);
```
