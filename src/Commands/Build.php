<?php

namespace Io238\ISOCountries\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Fluent;
use Io238\ISOCountries\Database\Migrations\IsoTables;
use Io238\ISOCountries\ISOCountriesServiceProvider;
use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


class Build extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Sqlite database with ISO country information in all configured translations.';

    /**
     * Temporary storage for model relationships.
     *
     * @var mixed
     */
    protected $relations;


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Country::unsetEventDispatcher();
        Language::unsetEventDispatcher();
        Currency::unsetEventDispatcher();

        $this->info('Reset DB file...');
        $this->resetDatabase();

        $this->info('Create tables for eloquent models...');
        $this->createTables();

        $this->info('Create pivot tables for relationships...');
        $this->createPivotTables();

        $this->info('Load country data...');
        $this->loadCountries();

        $this->info('Load currency data...');
        $this->loadCurrencies();

        $this->info('Load language data...');
        $this->loadLanguages();

        $this->info('Build data relations...');
        $this->storeRelations();

        $this->info('Load translations for countries...');
        $this->loadNameTranslations(Country::class);

        $this->info('Load translations for languages...');
        $this->loadNameTranslations(Language::class);

        $this->info('Load translations for currencies...');
        $this->loadNameTranslations(Currency::class);

        $this->info('Copy database file...');
        $this->copyDatabaseFile();

        return self::SUCCESS;
    }


    private function resetDatabase(): void
    {
        file_put_contents(config('database.connections.iso-countries.database'), '');

        $this->relations = collect();
    }


    private function createTables(): void
    {
        Schema::connection('iso-countries')->create('countries', function (Blueprint $table) {
            $table->string('id', 2)->comment('ISO 3166-1 alpha-2')->primary();
            $table->smallInteger('numeric')->index();
            $table->string('alpha3', 3)->index();
            $table->json('name');
            $table->string('native_name')->nullable();
            $table->string('capital')->nullable();
            $table->string('top_level_domain')->nullable();
            $table->string('calling_code')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->float('lat')->nullable();
            $table->float('lon')->nullable();
            $table->string('demonym')->nullable();
            $table->unsignedInteger('area')->nullable();
            $table->unsignedInteger('population')->nullable();
            $table->string('emoji_flag')->nullable();
            $table->boolean('is_independent')->nullable();
            $table->boolean('is_un_member')->nullable();
            $table->boolean('is_eu_member')->nullable();
            $table->string('ioc', 3)->index()->nullable();
            $table->string('fifa', 3)->index()->nullable();
            $table->string('start_of_week')->nullable();
        });

        Schema::connection('iso-countries')->create('languages', function (Blueprint $table) {
            $table->string('id', 2)->comment('ISO 639-1')->primary();
            $table->string('iso639_2', 3)->index();
            $table->string('iso639_2b', 3)->nullable()->index();
            $table->string('name');
            $table->string('native_name')->nullable();
            $table->string('family')->nullable();
            $table->string('wiki_url')->nullable();
        });

        Schema::connection('iso-countries')->create('currencies', function (Blueprint $table) {
            $table->string('id', 3)->comment('ISO 4217')->primary();
            $table->string('name');
            $table->string('name_plural');
            $table->string('symbol');
            $table->string('symbol_native');
            $table->unsignedTinyInteger('decimal_digits')->default(2);
            $table->unsignedTinyInteger('rounding')->default(0);
        });
    }


    private function createPivotTables(): void
    {
        Schema::connection('iso-countries')->create('country_language', function (Blueprint $table) {
            $table->string('country_id', 2)->index();
            $table->string('language_id', 2)->index();

            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::connection('iso-countries')->create('country_currency', function (Blueprint $table) {
            $table->string('country_id', 2)->index();
            $table->string('currency_id', 3)->index();

            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnDelete();
        });

        Schema::connection('iso-countries')->create('country_country', function (Blueprint $table) {
            $table->string('country_id', 2)->index();
            $table->string('neighbour_id', 2)->index();

            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->foreign('neighbour_id')->references('id')->on('countries')->cascadeOnDelete();
        });
    }


    private function loadCountries(): void
    {
        Country::unguard();

        $countries = collect(json_decode(file_get_contents(__DIR__ . '/../../data/restcountries-v2.json'), true));

        foreach ($countries as $country) {
            Country::create([
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
                'lat'              => $country['latlng'][0] ?? null,
                'lon'              => $country['latlng'][1] ?? null,
                'demonym'          => $country['demonym'],
                'area'             => (($country['area'] ?? null) < 0) ? null : ($country['area'] ?? null),
                'population'       => $country['population'],
                'is_independent'   => $country['independent'],
                'is_eu_member'     => collect($country['regionalBlocs'] ?? [])->where('acronym', 'EU')->count(),
            ]);

            $this->relations->put($country['alpha2Code'], [
                'borders'        => $country['borders'] ?? [],
                'currency_codes' => collect($country['currencies'] ?? [])->pluck('code')->toArray(),
                'language_codes' => collect($country['languages'] ?? [])->pluck('iso639_2')->toArray(),
            ]);
        }

        $countries = collect(json_decode(file_get_contents(__DIR__ . '/../../data/restcountries-v3.1.json'), true));

        foreach ($countries as $country) {
            Country::whereId($country['cca2'])->update([
                'population'    => $country['population'],
                'emoji_flag'    => $country['flag'] ?? null,
                'is_un_member'  => $country['unMember'],
                'fifa'          => $country['fifa'] ?? null,
                'ioc'           => $country['cioc'] ?? null,
                'start_of_week' => $country['startOfWeek'] ?? null,
            ]);
        }

        unset($countries);
    }


    private function loadCurrencies(): void
    {
        Currency::unguard();

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

        unset($currencies);
    }


    private function loadLanguages(): void
    {
        Language::unguard();

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

        unset($languages);
    }


    private function storeRelations(): void
    {
        Country::all()->each(function ($country) {
            $country->neighbours()->syncWithoutDetaching(
                Country::query()->whereIn('alpha3', $this->relations->get($country->id)['borders'] ?? [])->pluck('id')
            );
        });

        Country::all()->each(function ($country) {
            $country->currencies()
                ->syncWithoutDetaching(Currency::find($this->relations->get($country->id)['currency_codes'] ?? [])
                    ->pluck('id'));
        });

        Country::all()->each(function ($country) {
            $country->languages()->syncWithoutDetaching(
                Language::whereIn('iso639_2', $this->relations->get($country->id)['language_codes'] ?? [])->pluck('id')
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
                $item = $model::find($id)?->setTranslation('name', $locale, $name)->saveQuietly();
            }

        }
    }


    private function copyDatabaseFile(): void
    {
        copy(ISOCountriesServiceProvider::getPackageDatabaseFile(), config('iso-countries.database_path'));
    }

}