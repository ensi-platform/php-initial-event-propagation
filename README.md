# PHP Initiator Propagation

Define factories to generate any kind of object or even arrays for unit tests.

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
        InitiatorDTO::fromScratch(userId: "1", userType: "admin")
    );
```

If you are not in entrypoint context to need to get Initiator from `X-Initiator` request header instead of creating it from scratch:

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

### Laravel 

You can use build-in `Ensi\InitiatorPropagation\ParseInitiatorHeaderLaravelMiddleware` to populate `InitiatorHolder` with data from incoming request.
It's also recommended to add `$this->app->instance(InitiatorHolder::class, InitiatorHolder::getInstance());` to one of your service providers to make `InitiatorHolder` singleton injectable via Laravel Service Container

### Guzzle

You can use built-in `Ensi\InitiatorPropagation\PropagateInitiatorGuzzleMiddleware` to propagate `X-Initiator` header to every outcomming guzzle request made by the given Guzzle handler: `$stack->push(new PropagateInitiatorGuzzleMiddleware());`


## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Testing

1. composer install
2. npm i
3. composer test

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
