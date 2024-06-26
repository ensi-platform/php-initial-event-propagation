<?php

use Ensi\InitialEventPropagation\InitialEventDTO;

it('serializing success', function () {
    $dto = new InitialEventDTO(
        correlationId: "a95e90a3-82a3-4c77-82b2-4080a333456d",
        timestamp: "2021-11-17T16:08:26.385954Z",
        app: "mobile,api",
        entrypoint: "/api/v1/users/{id}",
        userId: "1",
        userType: "admin",
        misc: ""
    );

    $string = "correlationId={$dto->correlationId},timestamp={$dto->timestamp},app=mobile__COMMA__api,entrypoint={$dto->entrypoint},userId={$dto->userId},userType={$dto->userType}";

    expect($dto->serialize())->toEqual($string)
        ->and(InitialEventDTO::fromSerializedString($string))->toEqual($dto);
});

it('can be created from scratch without some fields', function () {
    $dto = InitialEventDTO::fromScratch(
        app: "mobile-api",
        entrypoint: "/api/v1/users/{id}",
        userId: "1",
        userType: "admin",
        misc: "",
    );

    expect($dto->correlationId)->not->toBeEmpty()
        ->and($dto->timestamp)->not->toBeEmpty()
        ->and($dto->toArray())->toMatchArray([
            'userId' => "1",
            'userType' => "admin",
            'app' => "mobile-api",
            'entrypoint' => "/api/v1/users/{id}",
            'misc' => "",
        ]);
});

it('empty value success', function () {
    $dto = new InitialEventDTO(
        correlationId: "a95e90a3-82a3-4c77-82b2-4080a333456d",
        timestamp: "2021-11-17T16:08:26.385954Z",
        app: "api",
        entrypoint: "/api/v1/users/{id}",
        userType: "admin",
    );

    $string = "correlationId={$dto->correlationId},timestamp={$dto->timestamp},app=api,entrypoint={$dto->entrypoint},userId=,userType={$dto->userType}";

    expect(InitialEventDTO::fromSerializedString($string))->toEqual($dto);
});
