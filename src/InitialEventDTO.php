<?php

namespace Ensi\InitialEventPropagation;

use DateTime;
use DateTimeZone;
use Ramsey\Uuid\Uuid;

class InitialEventDTO
{
    public function __construct(
        /** Unique identifier of event, e.g "a95e90a3-82a3-4c77-82b2-4080a333456d" */
        public string $correlationId,

        /** UTC-based ISO-8601 datetime of initial event, e.g "2021-11-17T16:08:26.385954Z" */
        public string $timestamp,

        /** Slug name of the application where initial event took place, e.g "api-gateway" */
        public string $app,

        /** Name of API endpoint or console command name, e.g "/api/v1/users/{id}" */
        public string $entrypoint,

        /** Guid or autoincrement user id of initiator, e.g "1" */
        public string $userId = '',

        /** User type of initiator if there are different types of users in your system, e.g "admin" */
        public string $userType = '',

        /** If there is a "login as another user" functionality in your system you can use this field as a place to store a real user id, e.g "1" */
        public string $realUserId = '',

        /** if there is a "login as another user" functionality in your system you can use this field as a place to store a real user type, e.g "admin" */
        public string $realUserType = '',

        /** Not used by package by default. This field can be used if there is a need to pass some additional data with InitialEventData, e.g "foo:bar;foo2:baz" */
        public string $misc = '',
    ) {
    }

    public static function fromScratch(
        string $app,
        string $entrypoint,
        string $userId = '',
        string $userType = '',
        string $realUserId = '',
        string $realUserType = '',
        string $misc = '',
        string $correlationId = '',
        string $timestamp = '',
    ): static {
        return new static(
            correlationId: $correlationId ?: Uuid::uuid4()->toString(),
            timestamp: $timestamp ?: (new DateTime())->setTimezone(new DateTimeZone("UTC"))->format('Y-m-d\TH:i:s.u\Z'),
            app: $app,
            entrypoint: $entrypoint,
            userId: $userId,
            userType: $userType,
            realUserId: $realUserId,
            realUserType: $realUserType,
            misc: $misc,
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
            realUserType: $params['realUserType'] ?? '',
            misc: $params['misc'] ?? ''
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
            'misc' => $this->misc,
        ];
    }
}
