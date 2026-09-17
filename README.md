# SCRU64 IDs for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/grantholle/scru64-laravel.svg?style=flat-square)](https://packagist.org/packages/grantholle/scru64-laravel)
[![Tests](https://github.com/grantholle/scru64-laravel/actions/workflows/run-tests.yml/badge.svg)](https://github.com/grantholle/scru64-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/grantholle/scru64-laravel.svg?style=flat-square)](https://packagist.org/packages/grantholle/scru64-laravel)

Use [SCRU64](https://github.com/scru64/spec) identifiers as Eloquent primary keys, the same way you'd use Laravel's `HasUuids`. Built on [grantholle/scru64](https://github.com/grantholle/scru64).

SCRU64 IDs are 12-character, case-insensitive, time-sortable strings (`0u375nxqh5cq`) that also fit in a signed `BIGINT`.

## Installation

```bash
composer require grantholle/scru64-laravel
```

It works out of the box on a single server. If more than one server generates IDs, give each one a unique node ID in its `.env`, or uniqueness is not guaranteed:

```dotenv
SCRU64_NODE_SPEC=42/8
```

The format is `<node_id>/<node_id_size>`; see the [scru64 README](https://github.com/grantholle/scru64#node-spec) for details. You can also publish the config file:

```bash
php artisan vendor:publish --tag="scru64-laravel-config"
```

## Usage

```php
use GrantHolle\Scru64Laravel\Concerns\HasScru64Ids;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasScru64Ids;
}
```

```php
Schema::create('posts', function (Blueprint $table) {
    $table->scru64(); // char('id', 12)->primary()
    // ...
});

Schema::create('comments', function (Blueprint $table) {
    $table->scru64();
    $table->foreignScru64('post_id')->constrained();
});
```

`scru64($column = 'id')` and `foreignScru64($column)` are Blueprint macros; the foreign variant behaves like `foreignUuid()`.

On MySQL/MariaDB, IDs are lowercase base36, so an ASCII binary collation keeps the column and its indexes compact and makes comparisons cheaper:

```php
$table->scru64()->charset('ascii')->collation('ascii_bin');
$table->foreignScru64('post_id')->charset('ascii')->collation('ascii_bin')->constrained();
```

The trait sets `$incrementing = false` and `$keyType = 'string'`, fills the key on create, and makes route model binding 404 on malformed IDs, exactly like `HasUuids`. Override `uniqueIds()` to generate IDs for additional columns:

```php
public function uniqueIds(): array
{
    return ['id', 'public_id'];
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Grant Holle](https://github.com/grantholle)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
