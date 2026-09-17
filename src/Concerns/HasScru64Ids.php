<?php

namespace GrantHolle\Scru64Laravel\Concerns;

use GrantHolle\Scru64\Scru64;
use GrantHolle\Scru64\Scru64Id;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use InvalidArgumentException;

trait HasScru64Ids
{
    use HasUniqueStringIds {
        resolveRouteBindingQuery as private baseResolveRouteBindingQuery;
    }

    /**
     * Generate a new SCRU64 ID for the model.
     */
    public function newUniqueId(): string
    {
        return (string) Scru64::generate();
    }

    /**
     * Retrieve the model for a bound value, normalizing case since SCRU64 IDs are case-insensitive.
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $column = $field ?: $this->getRouteKeyName();

        if (is_string($value) && in_array($column, $this->uniqueIds())) {
            $value = strtolower($value);
        }

        return $this->baseResolveRouteBindingQuery($query, $value, $field);
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
