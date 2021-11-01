<?php

namespace Ensi\InitiatorPropagation;

use Ramsey\Uuid\Uuid;

class InitiatorDTO
{
    public function __construct(
        public string $correlationId,
        public int $timestamp,
        public string $userId = '',
        public string $userType = '',
        public string $realUserId = '',
        public string $realUserType = '',
    ) {
    }

    public static function fromScratch(
        string $userId = '',
        string $userType = '',
        string $realUserId = '',
        string $realUserType = '',
        string $correlationId = ''
    ): static {
        return new static(
            correlationId: $correlationId ?: Uuid::uuid4()->toString(),
            timestamp: (int) hrtime(true),
            userId: $userId,
            userType: $userType,
            realUserId: $realUserId,
            realUserType: $realUserType
        );
    }

    public static function fromSerializedString(string $serializedData): static
    {
        $params = [];
        ray($serializedData);
        foreach (explode(",", $serializedData) as $keyWithValue) {
            [$key, $value] = explode("=", $keyWithValue);
            $params[$key] = $value ?? null;
        }

        return new static(
            correlationId: $params['correlationId'],
            timestamp: $params['timestamp'],
            userId: $params['userId'] ?? '',
            userType: $params['userType'] ?? '',
            realUserId: $params['realUserId'] ?? '',
            realUserType: $params['realUserType'] ?? ''
        );
    }

    public function serialize(): string
    {
        $array = array_filter($this->toArray());

        return implode(',', array_map(fn ($key, $value) => "$key=$value", array_keys($array), array_values($array)));
    }

    public function toArray(): array
    {
        return [
            'correlationId' => $this->correlationId,
            'timestamp' => $this->timestamp,
            'userId' => $this->userId,
            'userType' => $this->userType,
            'realUserId' => $this->realUserId,
            'realUserType' => $this->realUserType,
        ];
    }
}
