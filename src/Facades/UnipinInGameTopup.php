<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buatin\LaravelUnipin\UnipinInGameTopup
 * @method static getGameList()
 * @method static getGameDetail(string $string)
 * @method static validateUser(string $string, array $fields = [])
 * @method static createOrder(string $referenceNo, string $gameCode, string $denomId, array $fields = [])
 * @method static orderInquiry(string $referenceNo)
 */
class UnipinInGameTopup extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unipin-in-game-topup';
    }
}
