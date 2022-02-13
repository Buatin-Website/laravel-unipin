<?php

use Buatin\LaravelUnipin\Facades\UnipinInGameTopup;
use function Pest\Faker\faker;

beforeAll(function () {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
});

it('can get in game topup list', function () {
    $gameList = UnipinInGameTopup::getGameList();

    expect($gameList)->toBeArray()->toHaveKey(0);
});

it('can\'t get in game topup detail (game not found)', function () {
    $gameDetail = UnipinInGameTopup::getGameDetail(faker()->userName);

    expect($gameDetail)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid Game Code',
            'code' => 704
        ],
    ]);
});

it('can get in game topup detail', function () {
    $gameDetail = UnipinInGameTopup::getGameDetail('GHI_ID');

    expect($gameDetail)->toBeArray()->toMatchArray([
        'status' => true,
    ]);
});

it('can\'t validate user (Invalid Game Code)', function () {
    $validateUser = UnipinInGameTopup::validateUser(faker()->userName, [
        'fields' => [
            'userid' => faker()->userName,
        ],
    ]);

    expect($validateUser)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid Game Code',
            'code' => 704
        ],
    ]);
});

it('can\'t validate user (Invalid User ID)', function () {
    $validateUser = UnipinInGameTopup::validateUser('MLBBD_ID', [
        'userid' => faker()->userName,
        'zoneid' => faker()->numberBetween(1, 9),
    ]);

    expect($validateUser)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid User ID or Zone ID',
            'code' => 727
        ],
    ]);
});

it('can\'t validate user (Missing Parameter - zoneid)', function () {
    $validateUser = UnipinInGameTopup::validateUser('MLBBD_ID', [
        'userid' => faker()->userName
    ]);

    expect($validateUser)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Missing Parameter - zoneid',
            'code' => 701
        ],
    ]);
});

it('can validate user', function () {
    $validateUser = UnipinInGameTopup::validateUser('MLBBD_ID', [
        'userid' => 'testuser123',
        'zoneid' => 1,
    ]);

    expect($validateUser)->toMatchArray([
        'status' => true,
    ])->toBeArray();
});

it('can\'t create order (Invalid Game Code)', function () {
    $createOrder = UnipinInGameTopup::createOrder(
        faker()->uuid,
        faker()->userName,
        faker()->randomNumber(),
        [
            'userid' => 'testuser123',
            'zoneid' => 1,
        ]
    );

    expect($createOrder)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid Game Code',
        ],
    ]);
});

it('can\'t create order (Invalid denomination_id)', function () {
    $createOrder = UnipinInGameTopup::createOrder(
        faker()->uuid,
        'MLBBD_ID',
        faker()->randomNumber(),
        [
            'userid' => 'testuser123',
            'zoneid' => 1,
        ]
    );

    expect($createOrder)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid denomination_id',
            'code' => 707
        ],
    ]);
});

it('can create order', function () {
    $createOrder = UnipinInGameTopup::createOrder(
        faker()->uuid,
        'MLBBD_ID',
        1246,
        [
            'userid' => 'testuser123',
            'zoneid' => 1,
        ]
    );

    expect($createOrder)->toMatchArray([
        'status' => true,
    ])->toBeArray();
});