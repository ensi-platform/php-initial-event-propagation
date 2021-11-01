<?php

namespace Ensi\InitiatorPropagation;

use Psr\Http\Message\RequestInterface;

class PropagateInitiatorGuzzleMiddleware
{
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, $options) use ($handler) {
            $inititiator = InitiatorHolder::getInstance()->getInitiator();

            return $handler(
                $inititiator ? $request->withHeader(Config::REQUEST_HEADER, $inititiator->serialize()) : $request,
                $options
            );
        };
    }
}
