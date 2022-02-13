<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buatin\LaravelUnipin\Unipin
 * @method static request(string $string, array $array = [])
 * @method static fetchGame()
 */
class Unipin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unipin';
    }
}
