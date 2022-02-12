<?php

use Buatin\LaravelUnipin\Facades\UnipinInGameTopup;

it('can get in game topup list', function () {
    $gameList = UnipinInGameTopup::getGameList();

    expect($gameList)->toBeArray();
});

it('can\'t get in game topup detail (game not found)', function () {
    $gameDetail = UnipinInGameTopup::getGameDetail('game-not-found');

    expect($gameDetail)->toMatchArray([
        'status' => false,
        'error' => [
            'message' => 'Invalid Game Code',
            'code' => 704,
        ],
    ]);
});

it('can get in game topup detail', function () {
    $gameDetail = UnipinInGameTopup::getGameDetail('GHI_ID');

    expect($gameDetail)->toBeArray()->toMatchArray([
        'status' => true,
    ]);
});
