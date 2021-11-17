<?php

use Ensi\InitiatorPropagation\InitiatorDTO;

it('replaces commas with placeholder during serialization', function () {
    $dto = new InitiatorDTO(
        correlationId: "a95e90a3-82a3-4c77-82b2-4080a333456d",
        startedAt: "2021-11-17T16:08:26.385954Z",
        userId: "1",
        userType: "admin",
        app: "mobile,api",
        entrypoint: "/api/v1/users/{id}"
    );

    $expected = 'correlationId=a95e90a3-82a3-4c77-82b2-4080a333456d,startedAt=2021-11-17T16:08:26.385954Z,app=mobile__COMMA__api,entrypoint=/api/v1/users/{id},userId=1,userType=admin';

    expect($dto->serialize())->toEqual($expected);
});

it('can be created back from serialized string', function () {
    $serializedString = 'correlationId=a95e90a3-82a3-4c77-82b2-4080a333456d,startedAt=2021-11-17T16:08:26.385954Z,app=mobile__COMMA__api,entrypoint=/api/v1/users/{id},userId=1,userType=admin';
    $expected = new InitiatorDTO(
        correlationId: "a95e90a3-82a3-4c77-82b2-4080a333456d",
        startedAt: "2021-11-17T16:08:26.385954Z",
        userId: "1",
        userType: "admin",
        app: "mobile,api",
        entrypoint: "/api/v1/users/{id}"
    );

    expect(InitiatorDTO::fromSerializedString($serializedString))->toEqual($expected);
});

it('can be created from scratch without some fields', function () {
    $dto = InitiatorDTO::fromScratch(
        userId: "1",
        userType: "admin",
        app: "mobile-api",
        entrypoint: "/api/v1/users/{id}"
    );

    expect($dto->correlationId)->not->toBeEmpty();
    expect($dto->startedAt)->not->toBeEmpty();
    expect($dto->toArray())->toMatchArray([
        'userId' => "1",
        'userType' => "admin",
        'app' => "mobile-api",
        'entrypoint' => "/api/v1/users/{id}",
    ]);
});
