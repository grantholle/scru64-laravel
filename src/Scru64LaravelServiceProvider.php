<?php

namespace GrantHolle\Scru64Laravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use GrantHolle\Scru64Laravel\Commands\Scru64LaravelCommand;

class Scru64LaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scru64-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_scru64_laravel_table')
            ->hasCommand(Scru64LaravelCommand::class);
    }
}
