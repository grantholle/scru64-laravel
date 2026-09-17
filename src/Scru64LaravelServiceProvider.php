<?php

namespace GrantHolle\Scru64Laravel;

use GrantHolle\Scru64\Scru64;
use GrantHolle\Scru64\Scru64Generator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Scru64LaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('scru64-laravel')
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        Blueprint::macro('scru64', function (string $column = 'id'): ColumnDefinition {
            /** @var Blueprint $this */
            return $this->char($column, 12)->primary();
        });

        Blueprint::macro('foreignScru64', function (string $column): ForeignIdColumnDefinition {
            /** @var Blueprint $this */
            $definition = new ForeignIdColumnDefinition($this, [
                'type' => 'char',
                'name' => $column,
                'length' => 12,
            ]);

            $this->addColumnDefinition($definition);

            return $definition;
        });

        $spec = config('scru64-laravel.node_spec');

        if (is_string($spec) && $spec !== '') {
            Scru64::setGenerator(Scru64Generator::fromNodeSpec($spec));
        }
    }
}
