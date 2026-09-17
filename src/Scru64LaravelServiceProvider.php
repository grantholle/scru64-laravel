<?php

namespace GrantHolle\Scru64Laravel;

use GrantHolle\Scru64\Scru64;
use GrantHolle\Scru64\Scru64Generator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Support\ServiceProvider;

class Scru64LaravelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scru64-laravel.php', 'scru64-laravel');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/scru64-laravel.php' => config_path('scru64-laravel.php'),
        ], 'scru64-laravel-config');

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
