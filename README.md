# PHP Initiator Propagation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/initiator-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/initiator-propagation)
[![Tests](https://github.com/ensi-platform/php-initiator-propagation/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/php-initiator-propagation/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/initiator-propagation.svg?style=flat-square)](https://packagist.org/packages/ensi/initiator-propagation)

This package helps to propagate event initiator data to other backend services
[Laravel Bridge](https://github.com/ensi-platform/laravel-initiator-propagation/)

## Installation

You can install the package via composer:

`composer require ensi/initiator-propagation`

## Basic usage

First of all you need to create initiator data and place it to holder:

```php

use Ensi\InitiatorPropagation\InitiatorHolder;
use Ensi\InitiatorPropagation\InitiatorDTO;

InitiatorHolder::getInstance()
    ->setInitiator(
        InitiatorDTO::fromScratch(
            userId: "1",
            userType: "admin",
            app: "mobile-api-gateway",
            entrypoint: "/api/v1/users/{id}"
        )
    );
```

If you are not in initial entrypoint context to need to get Initiator from `X-Initiator` request header instead of creating it from scratch:

```php

use Ensi\InitiatorPropagation\Config;
use Ensi\InitiatorPropagation\InitiatorHolder;
use Ensi\InitiatorPropagation\InitiatorDTO;

InitiatorHolder::getInstance()
    ->setInitiator(
        InitiatorDTO::fromSerializedString($request->header(Config::REQUEST_HEADER))
    );
```

Next, extract DTO from holder (`InitiatorHolder::getInstance()->getInitiator`) and pass it to any futher outcomming requests (Guzzle, RabbitMQ, Kafka etc)
For example:
```php

use Ensi\InitiatorPropagation\Config;
use Ensi\InitiatorPropagation\InitiatorHolder;

function some_middleware(callable $handler)
{
    return function (RequestInterface $request, $options) use ($handler) {
        $inititiator = InitiatorHolder::getInstance()->getInitiator();

        return $handler(
            $inititiator ? $request->withHeader(Config::REQUEST_HEADER, $inititiator->serialize()) : $request,
            $options
        );
    };
}
```

### Guzzle

You can use built-in `Ensi\InitiatorPropagation\PropagateInitiatorGuzzleMiddleware` to propagate `X-Initiator` header to every outcomming guzzle request made by the given Guzzle handler: `$stack->push(new PropagateInitiatorGuzzleMiddleware());`


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
