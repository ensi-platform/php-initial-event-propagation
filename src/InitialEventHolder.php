<?php

namespace Ensi\InitialEventPropagation;

class InitialEventHolder
{
    private static array $instances = [];

    protected ?InitialEventDTO $initialEvent = null;

    public static function getInstance(): static
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }

        return self::$instances[$className];
    }

    public static function resetInstances(): void
    {
        self::$instances = [];
    }

    public function setInitialEvent(?InitialEventDTO $initialEvent): static
    {
        $this->initialEvent = $initialEvent;

        return $this;
    }

    public function getInitialEvent(): ?InitialEventDTO
    {
        return $this->initialEvent;
    }
}
