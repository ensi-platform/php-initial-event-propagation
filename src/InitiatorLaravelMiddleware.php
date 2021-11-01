<?php

namespace Ensi\InitiatorPropagation;

use Closure;
use Illuminate\Http\Request;

class InitiatorLaravelMiddleware
{
  public function handle(Request $request, Closure $next): mixed
  {
    if ($request->hasHeader(Config::REQUEST_HEADER)) {
        $initiator = InitiatorDTO::fromSerializedString($request->header(Config::REQUEST_HEADER));
        InitiatorHolder::getInstance()->setInitiator($initiator);
    }

    return $next($request);
  }
}
