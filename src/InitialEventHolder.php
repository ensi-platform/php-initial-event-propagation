<?php

namespace Ensi\InitialEventPropagation;

class InitialEventHolder
{
    private static array $instances = [];

    protected ?InitialEventDTO $initiator = null;

    public static function getInstance(): static
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }

        return self::$instances[$className];
    }

    public function setInitiator(InitialEventDTO $initiator): static
    {
        $this->initiator = $initiator;

        return $this;
    }

    public function getInitiator(): ?InitialEventDTO
    {
        return $this->initiator;
    }
}
