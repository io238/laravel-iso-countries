<?php

namespace Io238\ISOCountries\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;
use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


class IsoSeeder extends Seeder {

    protected array $tables = [
        'countries',
        'languages',
        'currencies',
        'country_language',
        'country_currency',
        'country_country',
    ];


    public function run(): void
    {

        // Truncate existing data

        collect($this->tables)->each(fn($table) => DB::table($table)->truncate());

        // Load ISO data from data files

        $this->loadCountries();

        $this->loadCurrencies();

        $this->loadLanguages();

        // Store relations

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

        return;

        // ==================================================================

        $this->command->info('Seeding Countries...');
        Country::query()->truncate();

        // Load countries and relationships as JSON from RestCountries API
        $response = Http::get('https://restcountries.com/v2/all');

        if ($response->successful()) {

            collect($response->json())->each(function ($country) {

                $country = new Fluent($country);

                $country_model = Country::create([
                    'id'               => $country['alpha2Code'],
                    'alpha_3'          => $country['alpha3Code'],
                    'name'             => $country['name'],
                    'native_name'      => $country['nativeName'] ?? null,
                    'capital'          => $country['capital'] ?? null,
                    'top_level_domain' => collect($country['topLevelDomain'])->first(),
                    'calling_code'     => collect($country['callingCodes'])->first(),
                    'region'           => $country['region'] ?? null,
                    'subregion'        => $country['subregion'] ?? null,
                    'borders'          => $country['borders'] ?? null,
                    'population'       => $country['population'] ?? null,
                    'lat'              => collect($country['latlng'])->first(),
                    'lon'              => collect($country['latlng'])->last(),
                    'demonym'          => $country['demonym'] ?? null,
                    'area'             => $country['area'] ?? null,
                    'gini'             => $country['gini'] ?? null,
                ]);

                // Attach relations
                $country_model->languages()->attach(Language::find(collect($country['languages'])->pluck('iso639_1')));

                $country_model->currencies()->attach(Currency::find(collect($country['currencies'])->pluck('code')));

                if ($country['borders']) {
                    $country_model->neighbours()->attach(Country::whereIn('alpha_3', $country['borders'])->get());
                }

            });

        }

        // Download name translations
        $this->downloadTranslations(Country::class);
        $this->downloadTranslations(Language::class);
        $this->downloadTranslations(Currency::class);

    }


    public function downloadTranslations($model): void
    {
        $this->command->info('Downloading translations for ' . $model);

        $locales = collect(config('app.locale'))->merge(config('app.fallback_locale'))
            ->merge(config('iso-countries.locales'))->unique();

        foreach ($locales as $locale) {

            $urls = [
                Country::class  => 'https://raw.githubusercontent.com/umpirsky/country-list/master/data/' . $locale . '/country.json',
                Language::class => 'https://raw.githubusercontent.com/umpirsky/language-list/master/data/' . $locale . '/language.json',
                Currency::class => 'https://raw.githubusercontent.com/umpirsky/currency-list/master/data/' . $locale . '/currency.json',
            ];

            $this->command->info('Loading names for locale "' . $locale . '"...');

            $response = Http::get($urls[$model]);

            if ($response->successful()) {
                foreach ($response->json() as $id => $name) {
                    $item = app($model)::find($id);

                    if ($item) {
                        $item->setTranslation('name', $locale, $name);
                        $item->save();
                    }
                }
            }
            else {
                $this->command->warn('Locale not available for download!');
            }

        }
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
                'top_level_domain' => $country['tld'][0] ?? null,
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

}
