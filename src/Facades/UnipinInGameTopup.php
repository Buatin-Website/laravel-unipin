<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buatin\LaravelUnipin\UnipinInGameTopup
 */
class UnipinInGameTopup extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unipin-in-game-topup';
    }
}
