<?php

namespace Ensi\InitiatorPropagation;

use InvalidArgumentException;
use Throwable;

class EmptySerializedFieldException extends InvalidArgumentException
{
    public function __construct(string $field, int $code = 0, ?Throwable $previous = null)
    {
        $message = "Initiator propagation error: \"{$field}\" is not set in serialized string";
        parent::__construct($message, $code, $previous);
    }
}
