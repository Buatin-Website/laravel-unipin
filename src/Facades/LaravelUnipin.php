<?php

namespace Buatin\LaravelUnipin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Buatin\LaravelUnipin\LaravelUnipin
 */
class LaravelUnipin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-unipin';
    }
}
