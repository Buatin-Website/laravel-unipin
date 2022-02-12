<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buatin\LaravelUnipin\UnipinInGameTopup
 */
class Unipin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unipin';
    }
}
