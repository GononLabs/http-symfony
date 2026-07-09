# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-07-09

### Added
- Initial release of the `gonon/http-symfony` adapter package.
- Implemented `SymfonyHttpClient` to bridge `Gonon\Core\Contracts\HttpClientInterface` with `symfony/http-client`.
- Fully integrated exception wrapping to map Symfony transport exceptions to `Gonon\Core\Exceptions\NetworkException`.
- Configured PHPStan (Level Max), PHPUnit, and Laravel Pint for code quality.
