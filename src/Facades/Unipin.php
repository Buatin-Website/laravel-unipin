<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buatin\LaravelUnipin\Unipin
 * @method static requestGame(string $string, array $array = [])
 * @method static requestVoucher(string $string, string $type, array $data = [])
 * @method static fetchGame()
 * @method static fetchVoucher()
 */
class Unipin extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unipin';
    }
}
