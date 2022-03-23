<?php

namespace Io238\ISOCountries\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Fluent;
use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


class IsoSeeder extends Seeder {

    public function run(): void
    {
        $this->command->info('Truncate tables...');
        $this->truncateTables();

        $this->command->info('Load country data...');
        $this->loadCountries();

        $this->command->info('Load currency data...');
        $this->loadCurrencies();

        $this->command->info('Load language data...');
        $this->loadLanguages();

        $this->command->info('Build data relations...');
        $this->storeRelations();

        $this->command->info('Load translations for countries...');
        $this->loadNameTranslations(Country::class);

        $this->command->info('Load translations for languages...');
        $this->loadNameTranslations(Language::class);

        $this->command->info('Load translations for currencies...');
        $this->loadNameTranslations(Currency::class);
    }


    private function truncateTables(): void
    {
        Country::query()->delete();
        Language::query()->delete();
        Currency::query()->delete();
    }


    private function loadCountries(): void
    {
        $countries = collect(json_decode(file_get_contents(__DIR__ . '/../../data/countries.json'), true));

        foreach ($countries as $country) {

            Country::query()->create([
                'id'               => $country['alpha2Code'],
                'alpha3'           => $country['alpha3Code'],
                'numeric'          => $country['numericCode'],
                'name'             => $country['name'],
                'native_name'      => $country['nativeName'],
                'capital'          => $country['capital'] ?? null,
                'top_level_domain' => $country['topLevelDomain'][0] ?? null,
                'calling_code'     => $country['callingCodes'][0] ?? null,
                'region'           => $country['region'],
                'subregion'        => $country['subregion'],
                'borders'          => $country['borders'] ?? [],
                'currency_codes'   => collect($country['currencies'] ?? [])->pluck('code')->toArray(),
                'language_codes'   => collect($country['languages'] ?? [])->pluck('iso639_2')->toArray(),
                'lat'              => $country['latlng'][0] ?? null,
                'lon'              => $country['latlng'][1] ?? null,
                'demonym'          => $country['demonym'],
                'area'             => (($country['area'] ?? null) < 0) ? null : ($country['area'] ?? null),
                'population'       => $country['population'],
                //'emoji_flag'       => $country['flag'],
                'is_independent'   => $country['independent'],
                //'is_un_member'     => $country['unMember'],
                //'is_eu_member'     => null,
            ]);

        }
    }


    private function loadCurrencies(): void
    {
        $currencies = collect(json_decode(file_get_contents(__DIR__ . '/../../data/currencies.json'), true))->first();

        foreach ($currencies as $code => $currency) {

            Currency::create([
                'id'             => $code,
                'name'           => $currency['name'],
                'name_plural'    => $currency['name_plural'],
                'symbol'         => $currency['symbol'],
                'symbol_native'  => $currency['symbol_native'],
                'decimal_digits' => $currency['decimal_digits'],
                'rounding'       => $currency['rounding'],
            ]);

        }
    }


    private function loadLanguages(): void
    {
        $languages = collect(json_decode(file_get_contents(__DIR__ . '/../../data/languages.json'), true));

        foreach ($languages as $language) {

            $language = new Fluent($language);

            Language::create([
                'id'          => $language['639-1'],
                'iso639_2'    => $language['639-2'],
                'iso639_2b'   => $language['639-2/B'],
                'name'        => $language['name'],
                'native_name' => $language['nativeName'],
                'family'      => $language['family'],
                'wiki_url'    => $language['wikiUrl'],
            ]);

        }
    }


    private function storeRelations(): void
    {
        Country::all()->each(function ($country) {
            $country->neighbours()->syncWithoutDetaching(Country::query()->whereIn('alpha3', $country->borders)->get());
        });

        Country::all()->each(function ($country) {
            $country->currencies()->syncWithoutDetaching(Currency::find($country->currency_codes));
        });

        Country::all()->each(function ($country) {
            $country->languages()->syncWithoutDetaching(
                Language::whereIn('iso639_2', $country->language_codes)->get()
            );
        });
    }


    private function loadNameTranslations($model): void
    {
        $locales = collect(config('app.locale'))
            ->merge(config('app.fallback_locale'))
            ->merge(config('iso-countries.locales'))
            ->unique();

        foreach ($locales as $locale) {

            $file = match ($model) {
                Country::class => __DIR__ . "/../../data/translations/countries/$locale/country.php",
                Language::class => __DIR__ . "/../../data/translations/languages/$locale/language.php",
                Currency::class => __DIR__ . "/../../data/translations/currencies/$locale/currency.php",
            };

            if ( ! file_exists($file)) {
                continue;
            }

            $translations = require $file;

            foreach ($translations as $id => $name) {
                $item = $model::find($id)?->setTranslation('name', $locale, $name)->save();
            }

        }
    }

}
