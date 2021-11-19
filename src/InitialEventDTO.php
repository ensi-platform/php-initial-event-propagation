<?php

namespace Ensi\InitialEventPropagation;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\Uuid;

class InitialEventDTO
{
    public function __construct(
        public string $correlationId,
        public string $timestamp,
        public string $app,
        public string $entrypoint,
        public string $userId = '',
        public string $userType = '',
        public string $realUserId = '',
        public string $realUserType = '',
    ) {
    }

    public static function fromScratch(
        string $app,
        string $entrypoint,
        string $userId = '',
        string $userType = '',
        string $realUserId = '',
        string $realUserType = '',
        string $correlationId = '',
        string $timestamp = ''
    ): static {
        return new static(
            correlationId: $correlationId ?: Uuid::uuid4()->toString(),
            timestamp: $timestamp ?: (new DateTime())->setTimezone(new DateTimeZone("UTC"))->format('Y-m-d\TH:i:s.u\Z'),
            app: $app,
            entrypoint: $entrypoint,
            userId: $userId,
            userType: $userType,
            realUserId: $realUserId,
            realUserType: $realUserType
        );
    }

    /**
     * @throws EmptySerializedFieldException if required fields are not set in serialized data
     */
    public static function fromSerializedString(string $serializedData): static
    {
        $params = [];
        foreach (explode(",", $serializedData) as $keyWithValue) {
            [$key, $value] = explode("=", $keyWithValue);
            $params[$key] = isset($value) ? str_replace('__COMMA__', ',', $value) : null;
        }

        return new static(
            correlationId: $params['correlationId'] ?? throw new EmptySerializedFieldException("correlationId"),
            timestamp: $params['timestamp'] ?? throw new EmptySerializedFieldException("timestamp"),
            app: $params['app'] ?? throw new EmptySerializedFieldException("app"),
            entrypoint: $params['entrypoint'] ?? throw new EmptySerializedFieldException("entrypoint"),
            userId: $params['userId'] ?? '',
            userType: $params['userType'] ?? '',
            realUserId: $params['realUserId'] ?? '',
            realUserType: $params['realUserType'] ?? ''
        );
    }

    public function serialize(): string
    {
        $array = array_filter($this->toArray());
        $mappedArray = array_map(
            fn ($key, $value) => $key . '=' . str_replace(',', '__COMMA__', $value),
            array_keys($array),
            array_values($array)
        );

        return implode(',', $mappedArray);
    }

    public function toArray(): array
    {
        return [
            'correlationId' => $this->correlationId,
            'timestamp' => $this->timestamp,
            'app' => $this->app,
            'entrypoint' => $this->entrypoint,
            'userId' => $this->userId,
            'userType' => $this->userType,
            'realUserId' => $this->realUserId,
            'realUserType' => $this->realUserType,
        ];
    }
}
