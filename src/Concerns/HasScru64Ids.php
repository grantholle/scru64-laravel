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
        return $this->idPrefix().Scru64::generate();
    }

    /**
     * The prefix prepended to generated IDs, e.g. "post_". Not part of the SCRU64 spec.
     */
    public function idPrefix(): string
    {
        return '';
    }

    /**
     * Retrieve the model for a bound value, normalizing case since SCRU64 IDs are case-insensitive.
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $column = $field ?: $this->getRouteKeyName();

        $prefix = $this->idPrefix();

        if (is_string($value) && in_array($column, $this->uniqueIds()) && str_starts_with($value, $prefix)) {
            $value = $prefix.strtolower(substr($value, strlen($prefix)));
        }

        return $this->baseResolveRouteBindingQuery($query, $value, $field);
    }

    /**
     * Determine if the given key is a valid SCRU64 ID.
     */
    protected function isValidUniqueId($value): bool
    {
        $prefix = $this->idPrefix();

        if (! is_string($value) || ! str_starts_with($value, $prefix)) {
            return false;
        }

        try {
            Scru64Id::fromString(substr($value, strlen($prefix)));

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }
}
