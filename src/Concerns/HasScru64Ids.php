<?php

namespace GrantHolle\Scru64Laravel\Concerns;

use GrantHolle\Scru64\Scru64;
use GrantHolle\Scru64\Scru64Id;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use InvalidArgumentException;

trait HasScru64Ids
{
    use HasUniqueStringIds;

    /**
     * Generate a new SCRU64 ID for the model.
     */
    public function newUniqueId(): string
    {
        return (string) Scru64::generate();
    }

    /**
     * Determine if the given key is a valid SCRU64 ID.
     */
    protected function isValidUniqueId($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        try {
            Scru64Id::fromString($value);

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }
}
