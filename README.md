# Laravel ISO Countries

[![Latest Version on Packagist](https://img.shields.io/packagist/v/io238/laravel-iso-countries.svg?label=Version)](https://packagist.org/packages/io238/laravel-iso-countries)
[![GitHub Tests Action Status](https://github.com/io238/laravel-iso-countries/workflows/Tests/badge.svg?branch=main)](https://github.com/io238/laravel-iso-countries/actions?query=workflow%3ATests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/io238/laravel-iso-countries.svg?label=Downloads)](https://packagist.org/packages/io238/laravel-iso-countries)

This package provides ready-to-use application models and seeds the database with ISO data from various sources. This
package can be used in multi-language apps and supports Country/Language/Currency names in almost any locale.

### Included datasets

- [Countries: ISO 3166 alpha 2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) (incl. names, capital, lat/lon
  coordinates, TLD, phone calling code, regions, population, gini, area)
- [Languages: ISO 639-1](https://en.wikipedia.org/wiki/ISO_639-1) (incl. names, language-family, wiki link)
- [Currencies: ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) (incl. names, symbol, decimal digits, rounding)

Unlike other packages, this one also includes relevant data relationships, such as:

```php
// Official languages spoken in Luxembourg ('LU')
Country::find('LU')->languages;

// Currencies used in Ghana ('GH')
Country::find('GH')->currencies;

// Countries that have Spanish ('es') as one of their official languages 
Language::find('es')->countries;

// Countries that use the Euro ('EUR') as currency
Currency::find('EUR')->countries;
```

## Installation

You can install the package via composer:

```bash
composer require io238/laravel-iso-countries
```

The latest version of this package requires PHP version 8.0 or above. If you need support for PHP 7.4, please install
version 2 of this package.

### Migrations

There is no need to run any migrations. All country data information is stored in a pre-compiled SQLITE database that is
stored within this package.

By default, this database includes all country/language/&currency names translated into English, German, French, and
Spanish. If you want to compile your own database with other languages,
please [see the instructions here](#data-updates--translations).

### Rebuilding the database

Country-level ISO data does not change very often. Nevertheless, if at any time you want to update the ISO data to the
latest available version, you can manually re-seed the tables:

### Config

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --provider="Io238\ISOCountries\ISOCountriesServiceProvider" --tag="config"
```

In the config you can define, which translations of country/language/currency names you want to store in your DB.

This is the contents of the published config file:

```php
[
    // Supported locales for names (countries, languages, currencies)
    'locales' => [
        'en',
        'de',
        'fr',
        'es',
    ],

    // Path for storing your own SQLITE database
    'database_path' => database_path('iso-countries.sqlite'),
];
```

After making changes to the config you need to re-build the database with the following command:

```bash
php artisan db:seed countries:build
```

This will create a new SQLITE database and stores it in your project at `database/iso-countries.sqlite`. Exclude this
file from `.gitignore` to have it available in all environments.

```gitignore
# database/.gitignore

*.sqlite*
!iso-countries.sqlite
```

## Usage

### Country model

```php
Country::find('AD');

Io238\ISOCountries\Models\Country {
     id: "AD",
     alpha3: "AND",
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
   }
```

### Language model

```php
Country::find('pt');

Io238\ISOCountries\Models\Language {
     id: "pt",
     iso639_2: "por",
     iso639_2b: null,
     name: "{"en":"Portuguese","de":"Portugiesisch","fr":"portugais","es":"portugu\u00e9s"}",
     native_name: "Português",
     family: "Indo-European",
     wiki_url: "https://en.wikipedia.org/wiki/Portuguese_language",
   }
```

### Currency model

```php
Currency::find('COP');

Io238\ISOCountries\Models\Currency {
     id: "COP",
     name: "{"en":"Colombian Peso","de":"Kolumbianischer Peso","fr":"peso colombien","es":"peso colombiano"}",
     name_plural: "Colombian pesos",
     symbol: "CO$",
     symbol_native: "$",
     decimal_digits: 0,
     rounding: 0,
   }
```

### Query relationships

All models have pre-defined many-to-many relationships that can be queried:

```php
// Retrieve languages that are spoken in Luxembourg
Country::find('LU')->languages;

Illuminate\Database\Eloquent\Collection {                                                                  
     all: [                                                                                                        
       Io238\ISOCountries\Models\Language {                                                                   
         id: "de",                                                                                                 
         iso639_2: "deu",                                                                                          
         iso639_2b: "ger",                                                                                         
         name: "{"en":"German","de":"Deutsch","fr":"allemand","es":"alem\u00e1n"}",                                
         native_name: "Deutsch",                                                                                   
         family: "Indo-European",                                                                                  
         wiki_url: "https://en.wikipedia.org/wiki/German_language",                                                
       },                                                                                                          
       Io238\ISOCountries\Models\Language {                                                                   
         id: "fr",                                                                                                 
         iso639_2: "fra",                                                                                          
         iso639_2b: "fre",                                                                                         
         name: "{"en":"French","de":"Franz\u00f6sisch","fr":"fran\u00e7ais","es":"franc\u00e9s"}",                 
         native_name: "français, langue française",                                                                
         family: "Indo-European",                                                                                  
         wiki_url: "https://en.wikipedia.org/wiki/French_language",                                                
       },                                                                                                          
       Io238\ISOCountries\Models\Language {                                                                   
         id: "lb",                                                                                                 
         iso639_2: "ltz",                                                                                          
         iso639_2b: null,                                                                                          
         name: "{"en":"Luxembourgish","de":"Luxemburgisch","fr":"luxembourgeois","es":"luxemburgu\u00e9s"}",       
         native_name: "Lëtzebuergesch",                                                                            
         family: "Indo-European",                                                                                  
         wiki_url: "https://en.wikipedia.org/wiki/Luxembourgish_language",                                         
       },                                                                                                          
     ],                                                                                                            
   }                                                                                                                                                                                                     
```

```php
// Retrieve all countries that use the Euro (EUR) as currency.
Currency::find('EUR')->countries->pluck('name');

Illuminate\Support\Collection {
     all: [
       "Andorra",
       "Austria",
       "Åland Islands",
       "Belgium",
       "St. Barthélemy",
       "Cyprus",
       "Germany",
       "Estonia",
       "Spain",
       "Finland",
       "France",
       "French Guiana",
       "Guadeloupe",
       "Greece",
       "Ireland",
       "Italy",
       "Lithuania",
       "Luxembourg",
       "Latvia",
       "Monaco",
       "Montenegro",
       "St. Martin",
       "Martinique",
       "Malta",
       "Netherlands",
       "St. Pierre & Miquelon",
       "Portugal",
       "Réunion",
       "Slovenia",
       "Slovakia",
       "San Marino",
       "French Southern Territories",
       "Vatican City",
       "Republic of Kosovo",
       "Mayotte",
       "Zimbabwe",
     ],
   }
```

### Localized names

Each of the package models (Country, Language, Currency) has a `->name` attribute, which will be displayed in the app's
locale, using the [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) package. The default
config will migrate localized names for `en`, `de`, `fr`, and `es`. This can be adjusted in the [config](#config). The
config `app.locale` and `app.fallback_locale` are automatically included.

```php
// Set the app locale
app()->setLocale('fr');

// Retrieve the top 10 countries in Africa (by population):
Io238\ISOCountries\Models\Country::where('region', 'Africa')->orderByDesc('population')->limit(10)->pluck('name');

// Country names will be returned according to the app locale (fr = French)
Illuminate\Support\Collection {
     all: [
       "Nigéria",
       "Éthiopie",
       "Égypte",
       "Congo-Kinshasa",
       "Afrique du Sud",
       "Tanzanie",
       "Kenya",
       "Algérie",
       "Soudan",
       "Ouganda",
     ],
   }
```

### Attribute casting

If you already use ISO codes in your models, you can enrich them by casting them as Country/Language/Currency model:

```php
use Io238\ISOCountries\Casts\Currency;

class MyModel{
    
    // ...

    protected $casts = [
        'currency' => Currency::class,
    ];
    
    // ...
}

// Now you can dynamically retrieve all meta data associated with the currency
MyModel::first()->currency;

Io238\ISOCountries\Models\Currency {
     id: "JPY",
     name: "{"en":"Japanese Yen","de":"Japanischer Yen","fr":"yen japonais","es":"yen"}",
     name_plural: "Japanese yen",
     symbol: "¥",
     symbol_native: "￥",
     decimal_digits: 0,
     rounding: 0,
   }


// When filling the model, the ISO code (string) or the model can be used 
MyModel::first()->update(['currency' => 'USD']);
MyModel::first()->update(['currency' => Io238\ISOCountries\Models\Currency::find('USD')]);
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Martin](https://github.com/io238)
- https://restcountries.com
- https://github.com/umpirsky/country-list
- https://github.com/umpirsky/language-list
- https://github.com/umpirsky/currency-list
- https://github.com/spatie/laravel-translatable
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
