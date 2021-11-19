# PHP Initial Event Propagation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/initial-event-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/initial-event-propagation)
[![Tests](https://github.com/ensi-platform/php-initial-event-propagation/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/php-initial-event-propagation/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/initial-event-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/initial-event-propagation)

This package helps to propagate initial event data to other backend services
[Laravel Bridge](https://github.com/ensi-platform/laravel-initial-event-propagation/)

## Installation

You can install the package via composer:

`composer require ensi/initial-event-propagation`

## Basic usage

First of all you need to create initial event data and place it to holder:

```php

use Ensi\InitialEventPropagation\InitialEventHolder;
use Ensi\InitialEventPropagation\InitialEventDTO;

InitialEventHolder::getInstance()
    ->setInitialEvent(
        InitialEventDTO::fromScratch(
            userId: "1",
            userType: "admin",
            app: "mobile-api-gateway",
            entrypoint: "/api/v1/users/{id}"
        )
    );
```

If you are not in initial entrypoint context to need to get initial event from `X-Initial-Event` request header instead of creating it from scratch:

```php

use Ensi\InitialEventPropagation\Config;
use Ensi\InitialEventPropagation\InitialEventHolder;
use Ensi\InitialEventPropagation\InitialEventDTO;

InitialEventHolder::getInstance()
    ->setInitialEvent(
        InitialEventDTO::fromSerializedString($request->header(Config::REQUEST_HEADER))
    );
```

Next, extract DTO from holder (`InitialEventHolder::getInstance()->getInitialEvent()`) and pass it to any futher outcomming requests (Guzzle, RabbitMQ, Kafka etc)
For example:
```php

use Ensi\InitialEventPropagation\Config;
use Ensi\InitialEventPropagation\InitialEventHolder;

function some_middleware(callable $handler)
{
    return function (RequestInterface $request, $options) use ($handler) {
        $inititiator = InitialEventHolder::getInstance()->getInitialEvent();

        return $handler(
            $inititiator ? $request->withHeader(Config::REQUEST_HEADER, $inititiator->serialize()) : $request,
            $options
        );
    };
}
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Testing

1. composer install
2. npm i
3. composer test

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
