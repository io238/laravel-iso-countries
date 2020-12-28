# Laravel ISO Countries

[![Latest Version on Packagist](https://img.shields.io/packagist/v/io238/laravel-iso-countries.svg)](https://packagist.org/packages/io238/laravel-iso-countries)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/io238/laravel-iso-countries/run-tests?label=tests)](https://github.com/io238/laravel-iso-countries/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/io238/laravel-iso-countries.svg)](https://packagist.org/packages/io238/laravel-iso-countries)


Countries by ISO codes with languages, currency and other meta information for Laravel. Provides ready-to-use application models and seeds the database with ISO data from various sources.


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

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --provider="Io238\ISOCountries\ISOCountriesServiceProvider" --tag="config"
```

In the config you can define, which translations of country/language/currency names you want to store in your DB.

This is the contents of the published config file:

```php
return [
    'locales' => [
        'en',
        'de',
        'fr',
        'es',
    ],
];
```

## Usage
```php
Country::find('AD');

Io238\ISOCountries\Models\Country {
     id: "AD",
     alpha_3: "AND",
     name: "{"en":"Andorra","de":"Andorra","fr":"Andorre","es":"Andorra"}",
     native_name: "Andorra",
     capital: "Andorra la Vella",
     top_level_domain: ".ad",
     calling_code: "376",
     region: "Europe",
     subregion: "Southern Europe",
     population: 78014,
     lat: 42.5,
     lon: 1.5,
     demonym: "Andorran",
     area: 468,
     gini: null,
   }
```


## Data

- [Countries: ISO 3166 alpha 2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
- [Languages: ISO 639-1](https://en.wikipedia.org/wiki/ISO_639-1)
- [Currencies: ISO 4217](https://en.wikipedia.org/wiki/ISO_4217)

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
