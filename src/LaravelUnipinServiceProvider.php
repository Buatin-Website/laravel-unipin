<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Commands\UnipinFetchGame;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelUnipinServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-unipin')
            ->hasConfigFile()
            ->hasMigrations([
                'create_unipin_game_products_table',
                'create_unipin_game_product_denominations_table',
                'create_unipin_game_product_fields_table',
            ])
            ->hasCommand(UnipinFetchGame::class);
    }

    public function registeringPackage()
    {
        $this->app->bind('unipin', function () {
            return new Unipin();
        });
        $this->app->bind('unipin-in-game-topup', function () {
            return new UnipinInGameTopup();
        });
    }
}
