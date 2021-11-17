<?php

namespace Ensi\InitiatorPropagation;

class InitiatorHolder
{
    private static array $instances = [];

    protected ?InitiatorDTO $initiator = null;

    public static function getInstance(): static
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }

        return self::$instances[$className];
    }

    public function setInitiator(InitiatorDTO $initiator): static
    {
        $this->initiator = $initiator;

        return $this;
    }

    public function getInitiator(): ?InitiatorDTO
    {
        return $this->initiator;
    }
}
