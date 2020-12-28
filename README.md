# Laravel ISO Countries

[![Latest Version on Packagist](https://img.shields.io/packagist/v/io238/laravel-iso-countries.svg?style=flat-square)](https://packagist.org/packages/io238/laravel-iso-countries)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/io238/laravel-iso-countries/run-tests?label=tests)](https://github.com/io238/laravel-iso-countries/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/io238/laravel-iso-countries.svg?style=flat-square)](https://packagist.org/packages/io238/laravel-iso-countries)


Countries by ISO codes with languages, currency and other meta information for Laravel. Provides ready-to-use application models or can be used as [Custom Cast](https://laravel.com/docs/8.x/eloquent-mutators#custom-casts). 


## Installation

You can install the package via composer:

```bash
composer require io238/laravel-iso-countries
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Io238\ISOCountries\ISOCountriesServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Io238\ISOCountries\ISOCountriesServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
// 
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Martin](https://github.com/io238)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
