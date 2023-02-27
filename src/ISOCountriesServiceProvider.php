<?php

namespace Io238\ISOCountries;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Io238\ISOCountries\Commands\BuildIsoDatabaseCommand;
use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


class ISOCountriesServiceProvider extends ServiceProvider
{

    static string $packageDatabaseFile = __DIR__ . '/../data/iso-countries.sqlite';


    public function boot()
    {
        // Create custom database connection

        // Use Sqlite database path from config, if it exists
        if (file_exists(config('iso-countries.database_path'))) {
            $this->app['config']->set('database.connections.iso-countries', [
                'driver'   => 'sqlite',
                'database' => realpath(config('iso-countries.database_path')),
            ]);
        } else {
            // Create empty Sqlite DB, if it does not exist yet
            if (! file_exists(static::getPackageDatabaseFile())) {
                file_put_contents(static::getPackageDatabaseFile(), '');
            }

            // Set connection to use the packaged database path
            $this->app['config']->set('database.connections.iso-countries', [
                'driver'   => 'sqlite',
                'database' => realpath(static::getPackageDatabaseFile()),
            ]);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__ . '/../config/iso-countries.php') => config_path('iso-countries.php'),
            ], 'config');
        }
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/iso-countries.php', 'iso-countries');

        $this->commands([
            BuildIsoDatabaseCommand::class,
        ]);

        $loader = AliasLoader::getInstance();

        $loader->alias('IsoCountry', Country::class);
        $loader->alias('IsoLanguage', Language::class);
        $loader->alias('IsoCurrency', Currency::class);
    }


    static public function getPackageDatabaseFile(): string
    {
        return static::$packageDatabaseFile;
    }

}
