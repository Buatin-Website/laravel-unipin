<?php

namespace Buatin\LaravelUnipin;

use Buatin\LaravelUnipin\Commands\LaravelUnipinCommand;
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
            ->hasViews()
            ->hasMigration('create_laravel-unipin_table')
            ->hasCommand(LaravelUnipinCommand::class);
    }
}
