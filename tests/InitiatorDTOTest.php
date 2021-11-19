<?php

use Ensi\InitialEventPropagation\InitialEventDTO;

it('replaces commas with placeholder during serialization', function () {
    $dto = new InitialEventDTO(
        correlationId: "a95e90a3-82a3-4c77-82b2-4080a333456d",
        timestamp: "2021-11-17T16:08:26.385954Z",
        userId: "1",
        userType: "admin",
        app: "mobile,api",
        entrypoint: "/api/v1/users/{id}",
        misc: ""
    );

    $expected = 'correlationId=a95e90a3-82a3-4c77-82b2-4080a333456d,timestamp=2021-11-17T16:08:26.385954Z,app=mobile__COMMA__api,entrypoint=/api/v1/users/{id},userId=1,userType=admin';

    expect($dto->serialize())->toEqual($expected);
});

it('can be created back from serialized string', function () {
    $serializedString = 'correlationId=a95e90a3-82a3-4c77-82b2-4080a333456d,timestamp=2021-11-17T16:08:26.385954Z,app=mobile__COMMA__api,entrypoint=/api/v1/users/{id},userId=1,userType=admin';
    $expected = new InitialEventDTO(
        correlationId: "a95e90a3-82a3-4c77-82b2-4080a333456d",
        timestamp: "2021-11-17T16:08:26.385954Z",
        userId: "1",
        userType: "admin",
        app: "mobile,api",
        entrypoint: "/api/v1/users/{id}",
        misc: ""
    );

    expect(InitialEventDTO::fromSerializedString($serializedString))->toEqual($expected);
});

it('can be created from scratch without some fields', function () {
    $dto = InitialEventDTO::fromScratch(
        userId: "1",
        userType: "admin",
        app: "mobile-api",
        entrypoint: "/api/v1/users/{id}",
        misc: "",
    );

    expect($dto->correlationId)->not->toBeEmpty();
    expect($dto->timestamp)->not->toBeEmpty();
    expect($dto->toArray())->toMatchArray([
        'userId' => "1",
        'userType' => "admin",
        'app' => "mobile-api",
        'entrypoint' => "/api/v1/users/{id}",
        'misc' => "",
    ]);
});
