<?php

namespace GrantHolle\Scru64Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GrantHolle\Scru64Laravel\Scru64Laravel
 */
class Scru64Laravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GrantHolle\Scru64Laravel\Scru64Laravel::class;
    }
}
